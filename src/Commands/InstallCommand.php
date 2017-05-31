<?php

namespace Nuntius\Commands;

use Nuntius\Nuntius;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * CLI command to install the bot.
 */
class InstallCommand extends Command  {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('nuntius:install')
      ->setDescription('Install nuntius')
      ->setHelp('Set up nuntius');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new SymfonyStyle($input, $output);

    if (!file_exists(__DIR__ . '/../../settings/credentials.local.yml')) {
      $question = new ConfirmationQuestion('The credentials yml file is missing. Would you like to generate the file?');

      if (!$io->askQuestion($question)) {
        $io->block('Well then, you need to create a copy of the file credentials.yml to credentials.local.yml and populate the values. Good luck!');
        return;
      }

      $this->generateCredentials($io);
    }

    $value = Nuntius::getSettings()->getSettings();
    $db = Nuntius::getRethinkDB();
    $operations = Nuntius::getDb()->getOperations();

    $io->section("Setting up the DB.");

    $operations->dbCreate($value['rethinkdb']['db']);
    $io->success("The DB was created");

    sleep(5);

    $io->section("Creating entities tables.");

    foreach (array_keys($value['entities']) as $scheme) {
      $operations->tableCreate($scheme);
      $io->success("The table {$scheme} has created");
    }

    // Run this again.
    $db->getTable('system')->insert(['id' => 'updates', 'processed' => []])->run($db->getConnection());

    Nuntius::getEntityManager()->get('system')->update('updates', ['processed' => array_keys(Nuntius::getUpdateManager()->getUpdates())]);

    $io->section("The install has completed.");
    $io->text('run php console.php nuntius:run');
  }

  /**
   * Generate the credentials file.
   *
   * @param SymfonyStyle $io
   *   The io object.
   */
  protected function generateCredentials(SymfonyStyle $io) {
    $settings = [];

    $settings['access_token'] = $io->ask('Enter Slack access token');

    $db_connection_ok = TRUE;
    while ($db_connection_ok) {
      $settings['rethinkdb']['host'] = $io->ask('Enter the address of the DB', 'localhost');
      $settings['rethinkdb']['port'] = $io->ask('Enter the port address of the DB', 28015);
      $settings['rethinkdb']['db'] = $io->ask('Enter the DB name', 'nuntius');
      $settings['rethinkdb']['api_key'] = $io->ask('Enter the API key', 'none');
      $settings['rethinkdb']['timeout'] = $io->ask('Enter the timeout for the DB connection', 30);

      if ($settings['rethinkdb']['api_key'] == 'none') {
        $settings['rethinkdb']['api_key'] = '';
      }

      try {
        @\r\connect($settings['rethinkdb']['host'], $settings['rethinkdb']['port'], $settings['rethinkdb']['db'], $settings['rethinkdb']['api_key'], $settings['rethinkdb']['timeout']);
        break;
      } catch (\Exception $e) {
        $io->error("Hmm.. It seems there is an error: " . $e->getMessage() . ". Let's start again.");
      }
    }

    $yml_content = YAML::dump($settings);
    $io->block("This are the settings:\n" . $yml_content);

    if (!$io->confirm('Do you approve the settings?')) {
      $io->block("OK. Let's start again");
      $this->generateCredentials($io);
    }

    $fs = new Filesystem();
    $fs->dumpFile(__DIR__ . '/../../settings/credentials.local.yml', $yml_content);
  }

}
