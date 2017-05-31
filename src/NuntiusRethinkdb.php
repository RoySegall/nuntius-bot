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

}
