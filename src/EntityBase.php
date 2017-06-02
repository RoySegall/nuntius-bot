<?php

namespace Nuntius;

/**
 * Abstract class for entities.
 */
abstract class EntityBase implements EntityBaseInterface {

  /**
   * The RethinkDB connection.
   *
   * @var \Nuntius\NuntiusRethinkdb
   */
  protected $db;

  /**
   * The entity ID.
   *
   * @var string
   */
  protected $entityID;

  /**
   * The storage of the DB.
   *
   * @var Db\DbStorageHandlerInterface
   */
  protected $storage;

  /**
   * EntityBase constructor.
   *
   * @param \Nuntius\NuntiusRethinkdb $db
   *   The RethinkDB connection.
   * @param string $entity_id
   *   The entity ID.
   */
  function __construct(NuntiusRethinkdb $db, $entity_id) {
    $this->db = $db;
    $this->entityID = $entity_id;
    $this->storage = Nuntius::getDb()->getStorage()->table($entity_id);
  }

  /**
   * Return the current instance object with the values from the DB.
   *
   * @param array $data
   *   The data representation of the object.
   *
   * @return $this
   *   The current object.
   */
  protected function createInstance($data) {
    $this_copy = clone $this;

    foreach ($data as $property => $value) {
      $this_copy->{$property} = $value;
    }

    return $this_copy;
  }

  /**
   * {@inheritdoc}
   */
  public function loadMultiple(array $ids = []) {
    $results = [];

    foreach ($this->storage->loadMultiple($ids) as $result) {
      \Kint::dump($result);
      $results[$result['id']] = $this->createInstance($result);
    }

    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function load($id) {
    $results = $this->loadMultiple([$id]);
    return reset($results);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $item) {
    return $this->storage->save($item);
  }

  /**
   * {@inheritdoc}
   */
  public function delete($id) {
    $this->storage->delete($id);
  }

  /**
   * {@inheritdoc}
   */
  public function update($data) {
    $this->storage->update($data);
  }

}
