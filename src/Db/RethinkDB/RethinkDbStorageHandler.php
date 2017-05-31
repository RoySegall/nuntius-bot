<?php

namespace Nuntius\Db\RethinkDB;

use Nuntius\Db\DbStorageHandlerInterface;
use Nuntius\Nuntius;

/**
 * RethinkDB storage handler.
 */
class RethinkDbStorageHandler implements DbStorageHandlerInterface {

  /**
   * The table name.
   *
   * @var string
   */
  protected $table;

  /**
   * RethinkDB service.
   *
   * @var \Nuntius\NuntiusRethinkdb
   */
  protected $rethinkdb;

  /**
   * The connection object.
   *
   * @var \r\Connection
   */
  protected $connection;

  /**
   * Constructing.
   */
  function __construct() {
    $this->rethinkdb = Nuntius::getRethinkDB();
    $this->connection = $this->rethinkdb->getConnection();
  }

  /**
   * {@inheritdoc}
   */
  public function table($table) {
    $this->table = $table;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function save($document) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function load($id) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function loadMultiple(array $ids = []) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function update($document) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function delete($id) {
  }

  /**
   * {@inheritdoc}
   */
  public function deleteMultiple(array $ids = []) {
  }

}
