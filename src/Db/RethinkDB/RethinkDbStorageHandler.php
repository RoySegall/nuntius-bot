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
   * Get the table handler.
   *
   * @return \r\Queries\Tables\Table
   */
  public function getTable() {
    return \r\db(Nuntius::getSettings()->getSetting('rethinkdb')['db'])
      ->table($this->table);
  }

  /**
   * {@inheritdoc}
   */
  public function save($document) {

    if (!isset($document['time'])) {
      $document['time'] = time();
    }

    $result = $this->getTable()->insert($document)->run($this->connection)->getArrayCopy();

    $document['id'] = reset($result['generated_keys']);

    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function load($id) {
    $items = $this->loadMultiple(array($id));

    return reset($items);
  }

  /**
   * {@inheritdoc}
   */
  public function loadMultiple(array $ids = []) {
    $query = Nuntius::getDb()->getQuery()
      ->table($this->table);

    if ($ids) {
      $query->condition('id', $ids, 'IN');
    }

    return $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function update($document) {
    $this->getTable()->get($document['id'])->update($document)->run($this->connection);
    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function delete($id) {
    $this->deleteMultiple([$id]);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteMultiple(array $ids = []) {
    $query = $this->getTable();

    if ($ids) {
      $query->getAll(\r\args($ids));
    }

    $query->delete()->run($this->connection);
  }

}
