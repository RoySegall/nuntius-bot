<?php

namespace Nuntius;

class EntityManager {

  /**
   * List of the entities.
   *
   * @var EntityBaseInterface[]
   */
  protected $entities;

  /**
   * The DB service.
   *
   * @var NuntiusRethinkdb
   */
  protected $db;

  /**
   * Constructing the entity manager.
   *
   * @param NuntiusRethinkdb $db
   *   List of all the entities.
   * @param NuntiusConfig $config
   *   The config service.
   */
  function __construct(NuntiusRethinkdb $db, NuntiusConfig $config) {
    $this->db = $db;

    $this->setEntities($config->getSetting('entities'));
  }

  /**
   * Entities setter.
   *
   * @param array $entities
   *   List of entities.
   *
   * @return $this
   *   The current instance.
   */
  public function setEntities($entities) {
    foreach ($entities as $entity => $namespace) {
      $this->entities[$entity] = new $namespace($this->db, $entity);
    }

    return $this;
  }

  /**
   * Get the entities.
   *
   * @return EntityBaseInterface[]
   *   The entities objects.
   */
  public function getEntities() {
    return $this->entities;
  }

  /**
   * Return the entity object.
   *
   * @param $entity_id
   *   The entity ID.
   *
   * @return EntityBaseInterface
   *   Entity controller.
   */
  public function get($entity_id) {
    return $this->entities[$entity_id];
  }

}
