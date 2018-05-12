<?php

namespace Nuntius\Db\RethinkDB;

use Symfony\Component\Console\Style\SymfonyStyle;

trait RethinkDbTraitInstallation {

  /**
   * Set the settings for RethinkDB.
   *
   * @param $settings
   *  The settings variable.
   */
  protected function setDbRethinkdbSettings(&$settings, SymfonyStyle $io) {
    $settings['rethinkdb']['host'] = $io->ask('Enter the address of the DB', 'localhost');
    $settings['rethinkdb']['port'] = $io->ask('Enter the port address of the DB', 28015);
    $settings['rethinkdb']['db'] = $io->ask('Enter the DB name', 'nuntius');
    $settings['rethinkdb']['api_key'] = $io->ask('Enter the API key', 'none');
    $settings['rethinkdb']['timeout'] = $io->ask('Enter the timeout for the DB connection', 30);

    if ($settings['rethinkdb']['api_key'] == 'none') {
      $settings['rethinkdb']['api_key'] = '';
    }
  }

  /**
   * Check the connection.
   *
   * @param $settings
   * @param SymfonyStyle $io
   *
   * @return bool
   */
  protected function checkDbRethinkdbSettings(&$settings, SymfonyStyle $io) {
    try {
      @\r\connect($settings['rethinkdb']['host'], $settings['rethinkdb']['port'], $settings['rethinkdb']['db'], $settings['rethinkdb']['api_key'], $settings['rethinkdb']['timeout']);
    } catch (\Exception $e) {
      $io->error("Hmm.. It seems there is an error: " . $e->getMessage() . ". Let's start again.");
      return FALSE;
    }

    return TRUE;
  }

}