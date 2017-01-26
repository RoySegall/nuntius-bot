<?php

namespace Nuntius;

use r;

class NuntiusRethinkdb {

  /**
   * @var r\Connection
   */
  protected $connection;

  /**
   * @var integer
   */
  protected $prefix;

  /**
   * The DB name.
   *
   * @var string
   */
  protected $db;

  /**
   * NuntiusRethinkdb constructor.
   *
   * @param $info
   *   DB info.
   */
  function __construct($info) {
    $this->db = $info['db'];
    try {
      $this->connection = \r\connect($info['host'], $info['port'], $info['db'], $info['api_key'], $info['timeout']);
    } catch (\Exception $e) {
      print($e->getMessage() . "\n");
    }
  }

  /**
   * @return int
   */
  public function getPrefix() {
    return $this->prefix;
  }

  /**
   * @param int $prefix
   *   The prefix of the tables.
   *
   * @return NuntiusRethinkdb
   */
  public function setPrefix($prefix) {
    $this->prefix = $prefix;
    return $this;
  }

  /**
   * Get the table of the name with the prefix. If there is.
   *
   * @param $table
   *   The table name.
   *
   * @return string
   *   The table name.
   */
  public function getTableName($table) {
    return $this->prefix ? $this->prefix . '_' . $table : $table;
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
   * Create a table in the DB.
   *
   * @param $table
   *   The table name.
   */
  public function createTable($table) {
    try {
      r\db($this->db)->tableCreate($this->getTableName($table))->run($this->connection);
    } catch (\Exception $e) {
      print($e->getMessage() . "\n");
    }
  }

  /**
   * Creating a DB.
   *
   * @param $db
   *   The DB name.
   */
  public function createDB($db) {
    try {
      r\dbCreate($db)->run($this->connection);
    } catch (\Exception $e) {
      print($e->getMessage() . "\n");
    }
  }

  /**
   * Adding entry to a table.
   *
   * @param $table
   *   The table name.
   * @param $array
   *   The record.
   */
  public function addEntry($table, $array) {
    r\db($this->db)
      ->table($this->getTableName($table))
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
      ->table($this->getTableName($table));
  }

  /**
   * Delete the table.
   *
   * @param $table
   *   The table name.
   */
  public function deleteTable($table) {
    r\db($this->db)
      ->tableDrop($this->getTableName($table))
      ->run($this->connection);
  }

}
