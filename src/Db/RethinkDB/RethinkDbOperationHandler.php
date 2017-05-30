<?php

namespace Nuntius\Db\RethinkDB;

use Nuntius\Db\DbOperationHandlerInterface;
use Nuntius\Nuntius;

/**
 * RethinkDB operation handler.
 */
class RethinkDbOperationHandler implements DbOperationHandlerInterface {

  /**
   * The rethinkDB service.
   *
   * @var \Nuntius\NuntiusRethinkdb
   */
  protected $rethinkDB;

  /**
   * The connection object.
   *
   * @var \r\Connection
   */
  protected $connection;

  /**
   * The DB name.
   *
   * @var string
   */
  protected $db;

  /**
   * Constructing.
   */
  function __construct() {
    $this->rethinkDB = @Nuntius::getRethinkDB();
    $this->connection = $this->rethinkDB->getConnection();
    $this->db = Nuntius::getSettings()->getSetting('rethinkdb')['db'];
  }

  /**
   * {@inheritdoc}
   */
  public function connected() {
    return $this->connection;
  }

  /**
   * {@inheritdoc}
   */
  public function getError() {
    return $this->rethinkDB->error;
  }

  /**
   * {@inheritdoc}
   */
  public function dbCreate($db) {
    \r\dbCreate($db)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function dbDrop($db) {
    \r\dbDrop($db)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function dbList() {
    return \r\dbList()->run($this->connection);
  }

  /**
   * {@inheritdoc}
   */
  public function dbExists($db) {
    return in_array($db, $this->dbList());
  }

  /**
   * {@inheritdoc}
   */
  public function tableCreate($table) {
    \r\db($this->db)->tableCreate($table)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function tableDrop($table) {
    \r\db($this->db)->tableDrop($table)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function tableList() {
    return \r\db($this->db)->tableList()->run($this->connection);
  }

  /**
   * {@inheritdoc}
   */
  public function tableExists($table) {
    return in_array($table, $this->tableList());
  }

  /**
   * {@inheritdoc}
   */
  public function indexCreate($table, $column) {
    \r\db($this->db)->table($table)->indexCreate($column)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function indexDrop($table, $column) {
    \r\db($this->db)->table($table)->indexDrop($column)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function indexList($table) {
    return \r\db($this->db)->table($table)->indexList()->run($this->connection);
  }

}
