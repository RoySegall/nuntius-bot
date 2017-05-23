<?php

namespace Nuntius\Commands;

use Nuntius\Nuntius;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Iterate over the updates.
 */
class RunCommand extends Command  {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('nuntius:run')
      ->setDescription('Starting the bot')
      ->setHelp('Running updates');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new SymfonyStyle($input, $output);
    // Bootstrapping.
    $client = Nuntius::bootstrap();
    $settings = Nuntius::getSettings();

    // Get the DB connection.
    $db = @Nuntius::getRethinkDB();

    if (!$db->getConnection()) {
      $text = 'The DB is not responding. Initialize the DB first and then start the bot.';

      if ($input->getOption('verbose')) {
        $text .= "\n" . $db->error;
      }
      else {
        $text .= ' Use --verbose form more info';
      }

      $io->error($text);
      return;
    }

    // Iterating over the plugins and register them for Slack events.
    foreach ($settings->getSetting('events') as $event => $namespace) {
      /** @var \Nuntius\NuntiusPluginAbstract $plugin */
      $plugin = new $namespace($client);

      $client->on($event, function ($data) use ($plugin) {
        $plugin->data = $data->jsonSerialize();

        $plugin->preAction();
        $plugin->action();
        $plugin->postAction();
      });
    }

    // Login something to screen when the bot started to work.
    $client->connect()->then(function () {
      echo "Nuntius started to work at " . date('d/m/Y H:i');
    });

    // Starting the work.
    Nuntius::run();
  }

}
