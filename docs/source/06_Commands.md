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

    $value = Nuntius::getSettings();
    $db = Nuntius::getRethinkDB();

    $io->section("Setting up the DB.");

    $db->createDB($value['rethinkdb']['db']);
    $io->success("The DB was created");

    sleep(5);

    $io->section("Creating entities tables.");

    foreach (array_keys($value['entities']) as $scheme) {
      $db->createTable($scheme);
      $io->success("The table {$scheme} has created");
    }

    // Run this again.
    $db->getTable('system')->insert(['id' => 'updates', 'processed' => []])->run($db->getConnection());

    Nuntius::getEntityManager()->get('system')->update('updates', ['processed' => array_keys(Nuntius::getUpdateManager()->getUpdates())]);

    $io->section("The install has completed.");
    $io->text('run php bot.php');
  }

}
```
