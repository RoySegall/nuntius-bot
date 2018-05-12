<?php

namespace Nuntius\Db\MongoDB;

use Symfony\Component\Console\Style\SymfonyStyle;

trait MongoDbTraitInstallation {

  /**
   * Set the settings for MongoDB.
   *
   * @param $settings
   * @param SymfonyStyle $io
   */
  protected function setDbMongodbSettings(&$settings, SymfonyStyle $io) {
    $settings['mongodb']['uri'] = $io->ask('Enter the URI of the server', 'mongodb://127.0.0.1/');
    $settings['mongodb']['db'] = $io->ask('Enter the DB name', 'nuntius');

    if ($username = $io->ask('Enter the username', FALSE)) {
      $settings['mongodb']['username'] = $username;
    }

    if ($password = $io->ask('Enter the password', FALSE)) {
      $settings['mongodb']['password'] = $password;
    }
  }

  /**
   * Check the connection for MongoDB.
   *
   * @param $settings
   * @param SymfonyStyle $io
   *
   * @return bool
   */
  protected function checkDbMongodbSettings(&$settings, SymfonyStyle $io) {
    $options = [];

    if (!empty($settings['mongodb']['username']) && !empty($settings['mongodb']['password'])) {
      // Setting up username and password.
      $options['username'] = $settings['mongodb']['username'];
      $options['password'] = $settings['mongodb']['password'];
    }

    try {
      $collection = new Client($settings['mongodb']['uri'], $options);
      $collection->listDatabases();
    } catch (\Exception $e) {
      $io->error("Hmm.. It seems there is an error: " . $e->getMessage() . ". Let's start again.");
      return FALSE;
    }

    return TRUE;
  }

}