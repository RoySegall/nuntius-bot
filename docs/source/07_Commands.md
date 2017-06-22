Commands are an easy way to add CLI integration. The commands based on the
Symfony console component so we won't go and explain the API. You can read about
it [here](https://symfony.com/doc/current/console.html)

Let's have a look on how to define:
```yml
commands:
  - '\Nuntius\Commands\UpdateCommand'
  - '\Nuntius\Commands\InstallCommand'
```

Let's have a look at the code that installs Nuntius for us:
```php
<?php

namespace Nuntius\Commands;

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
    $operations = Nuntius::getDb()->getOperations();
    $storage = Nuntius::getDb()->getStorage();

    $io->section("Setting up the DB.");

    if ($operations->dbExists($value['rethinkdb']['db'])) {
      $io->success("The DB already exists, skipping.");
    }
    else {
      $operations->dbCreate($value['rethinkdb']['db']);
      $io->success("The DB was created");
      sleep(5);
    }

    $io->section("Creating entities tables.");

    foreach (array_keys($value['entities']) as $table) {
      if ($operations->tableExists($table)) {
        $io->success("The table {$table} already exists, skipping.");
      }
      else {
        $operations->tableCreate($table);
        $io->success("The table {$table} has created");
      }
    }

    // Run this again.
    $storage->table('system')->save(['id' => 'updates', 'processed' => array_keys(Nuntius::getUpdateManager()->getUpdates())]);

    $io->section("The install has completed.");
    $io->text('run php console.php nuntius:run');
  }

}
```
