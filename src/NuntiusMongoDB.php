<?php

namespace Nuntius;
use MongoDB\Client;

/**
 * MongoDB layer manager.
 */
class NuntiusMongoDB {

  /**
   * @var \MongoDB\Database
   */
  protected $connection;

  /**
   * The DB name.
   *
   * @var string
   */
  protected $db;

  /**
   * The DB connection error.
   *
   * @var string
   */
  public $error;

  /**
   * @var Client
   */
  protected $client;

  /**
   * NuntiusRethinkdb constructor.
   *
   * @param NuntiusConfig $config
   *   The config service.
   */
  function __construct(NuntiusConfig $config) {
    $info = $config->getSetting('mongodb');

    $options = [];

    if (!empty($info['username']) && !empty($info['password'])) {
      // Setting up username and password.
      $options['username'] = $info['username'];
      $options['password'] = $info['password'];
    }

    $this->client = new Client($info['uri'], $options);

    $collection = $this->client;
    $this->connection = $collection->selectDatabase($info['db']);
  }

  /**
   * @return Client
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * Getting the connect object.
   *
   * @return \MongoDB\Database
   */
  public function getConnection() {
    return $this->connection;
  }

}
