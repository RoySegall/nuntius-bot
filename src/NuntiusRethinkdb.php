<?php

namespace Nuntius;

use r;

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

  function __construct($info) {
    $this->db = $info['database'];
    $this->connection = \r\connect($info['host'], $info['port'], $info['database'], $info['api_key'], $info['timeout']);
  }

  public function getConnection() {
    return $this->connection;
  }

  /**
   * Create a table in the DB.
   *
   * @param $table
   *   The table name.
   */
  public function createTable($table) {
    r\db($this->db)->tableCreate($table)->run($this->connection);
  }

  /**
   * Creating a DB.
   *
   * @param $db
   *   The DB name.
   */
  public function createDB($db) {
    r\dbCreate($db)->run($this->connection);
  }

  /**
   * Adding entry to a table.
   *
   * @param $string
   *   The table name.
   * @param $array
   *   The record.
   */
  public function addEntry($string, $array) {
    r\db($this->db)
      ->table($string)
      ->insert($array)
      ->run($this->connection);
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
