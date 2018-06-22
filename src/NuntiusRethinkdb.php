<?php

namespace Nuntius;

use r;

/**
 * RethinkDB layer manager.
 */
class NuntiusRethinkdb {

  /**
   * @var r\Connection
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
   * NuntiusRethinkdb constructor.
   *
   * @param NuntiusConfig $config
   *   The config service.
   */
  function __construct(NuntiusConfig $config) {
    $this->confg = $config;

      $info = $config->getSetting('rethinkdb');
      $this->db = $info['db'];

      try {
        $this->connection = \r\connect($info['host'], $info['port'], $info['db'], $info['api_key'], $info['timeout']);
      } catch (\Exception $e) {
        $this->error = $e->getMessage();
      }
  }

  /**
   * Getting the connect object.
   *
   * @return r\Connection
   */
  public function getConnection() {
    return $this->connection;
  }

  /**
   * Get a table object.
   *
   * @param $table
   *   The name of the table.
   *
   * @return r\Queries\Tables\Table
   */
  public function getTable($table) {
    return r\db($this->db)
      ->table($table);
  }

  /**
   * Closing the connection when serializing.
   *
   * Since this service will be injected as a property to an object that might
   * be cached we need to close the service when serializing the object.
   *
   * @return array
   * @throws r\Exceptions\RqlDriverError
   */
  public function ___sleep() {
    try {
      $this->connection->close(true);
    } catch (\Exception $e) {

    }

    return [];
  }

  /**
   * Opening a new connection when un-serializing.
   *
   * After this service was injected to an object we need to reconnect to the
   * DB.
   *
   * @throws \Exception
   */
  public function ___wakeup() {
    $this->__construct(Nuntius::container()->get('config'));
  }

}
