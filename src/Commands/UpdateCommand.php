<?php

namespace Nuntius\Commands;

use Nuntius\Nuntius;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateCommand extends Command  {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('nuntius:update')
      ->setDescription('Running updates')
      ->setHelp('Running updates');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $update_manager = Nuntius::getUpdateManager();

    $io = new SymfonyStyle($input, $output);

    if (!$updates = $update_manager->getUnProcessedUpdates()) {
      $io->error('No updates are available.');
      return;
    }

    $io->section('Running updates');
    $io->text('There are ' . count($updates) . ' updates: ');

    foreach ($updates as $update => $controller) {
      $output->writeln(['Update ' . $update . ': ' . $controller->description()]);
    }

    $helper = $this->getHelper('question');
    $question = new ConfirmationQuestion('Continue with this action - y/n? ', false);

    if (!$helper->ask($input, $output, $question)) {
      return;
    }

    $io->text(['running updates...']);

    foreach ($updates as $update => $controller) {
      try {
        // Running the update.
        $result = $controller->update();
      }
      catch (\Exception $e) {
        exit('Something went wrong: ' . $e->getMessage());
      }

      // Register it to the DB.
      $update_manager->addProcessedUpdate($update);

      // Output the result.
      $io->text($result);
    }

    $io->success(['Updated ran successfully!']);
  }

}
