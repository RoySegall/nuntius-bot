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

  function __construct() {
    $this->rethinkDB = Nuntius::getRethinkDB();
    $this->connection = $this->rethinkDB->getConnection();
  }

  /**
   * {@inheritdoc}
   */
  public function dbCreate($db) {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function dbDrop($db) {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function dbList() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function dbExists($db) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function tableCreate($table) {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function tableDrop($table) {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function tableList() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function tableExists($table) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function indexCreate($column, $table) {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function indexDrop($column, $table) {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function indexList($column, $table) {
    return [];
  }

}
