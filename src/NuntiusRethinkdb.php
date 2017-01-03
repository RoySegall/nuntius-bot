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

  function __construct() {
    $this->connection = r\connect('localhost');
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
    $this->db = $db;
    r\dbCreate($db)->run($this->connection);
  }

}
