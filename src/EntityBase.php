<?php

namespace Nuntius;
use Nuntius\Db\DbDispatcher;

/**
 * Abstract class for entities.
 */
abstract class EntityBase implements EntityBaseInterface {

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
   * @param \Nuntius\Db\DbDispatcher $db
   *   The RethinkDB connection.
   * @param string $entity_id
   *   The entity ID.
   */
  function __construct(DbDispatcher $db, $entity_id) {
    $this->entityID = $entity_id;
    $this->storage = $db->getStorage()->table($entity_id);
  }

  /**
   * Getter handler.
   *
   * Since this might be a NoSQL object we cannot predict the properties of the
   * class. In order to handle this one and prevent "Undefined property" errors
   * we need a getter that will check if the property exists or not.
   *
   * @param $name
   *  The name of the property.
   *
   * @return mixed
   *  Return the value of the property if exists, if not return NULL.
   */
  function __get($name) {
    if (!property_exists($this, $name)) {
      return NULL;
    }

    return $this->{$name};
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
  public function deleteMultiple(array $ids = []) {
    $this->storage->delete($ids);
  }

  /**
   * {@inheritdoc}
   */
  public function update($data) {
    $this->storage->update($data);
  }

}
