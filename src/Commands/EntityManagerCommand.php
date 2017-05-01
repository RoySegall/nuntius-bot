<?php

namespace Nuntius\Commands;

use Nuntius\Nuntius;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * CLI support to the entity manager service.
 */
class EntityManagerCommand extends Command {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('nuntius:entity')
      ->setDescription('Manage entities')
      ->setHelp('Manage entities from the terminal')
      ->addArgument('name', InputArgument::REQUIRED, 'The entity name.')
      ->addArgument('operation', InputArgument::OPTIONAL, 'What kind of operation you want to preform: list or live_view', 'list')
      ->addArgument('limit', InputArgument::OPTIONAL, 'Amount of entities to display', 25);
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new SymfonyStyle($input, $output);
    $arguments = $input->getArguments();
    $entities = Nuntius::getEntityManager()->getEntities();

    if (empty($entities[$arguments['name']])) {
      $io->error('The entity ' . $arguments['name'] . ' does not exists');
      return;
    }

    $results = Nuntius::getRethinkDB()
      ->getTable($arguments['name']);

    if ($arguments['operation'] == 'live_view') {

      $cursor = $results->changes()->run(Nuntius::getRethinkDB()->getConnection());

      $io->section('Starting live feeds');
      foreach ($cursor as $row) {
        var_dump($row->getArrayCopy());
      }

    }

    $results = $results
      ->limit($arguments['limit'])
      ->run(Nuntius::getRethinkDB()->getConnection());

    foreach ($results as $result) {
      var_dump($result->getArrayCopy());
    }

  }

}
