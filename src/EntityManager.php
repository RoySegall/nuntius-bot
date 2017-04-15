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
   * Constructing the entity manager.
   *
   * @param array $entities
   *   List of all the entities.
   */
  function __construct($entities) {
    $db = Nuntius::getRethinkDB();

    foreach ($entities as $entity => $namespace) {
      $this->entities[$entity] = new $namespace($db, $entity);
    }
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
