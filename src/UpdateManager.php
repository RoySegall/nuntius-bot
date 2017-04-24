<?php

namespace Nuntius;

/**
 * Manage updates.
 */
class UpdateManager {

  /**
   * List of updates.
   *
   * @var UpdateBaseInterface[]
   */
  protected $updates;

  /**
   * The DB service.
   *
   * @var NuntiusRethinkdb
   */
  protected $db;

  /**
   * The entity manager service.
   *
   * @var EntityManager
   */
  protected $entityManager;

  /**
   * Constructing the update manager.
   *
   * @param NuntiusRethinkdb $db
   *   The DB service.
   * @param EntityManager $entity_manager
   *   The entity manager service.
   * @param NuntiusConfig $config
   *   The config service.
   */
  function __construct(NuntiusRethinkdb $db, EntityManager $entity_manager, NuntiusConfig $config) {
    $this->db = $db;
    $this->entityManager = $entity_manager;

    $this->setUpdates($config->getSetting('updates'));
  }

  /**
   * Set updates.
   *
   * @param array $updates
   *   The updates list.
   *
   * @return $this
   *   The current object.
   */
  protected function setUpdates($updates) {
    foreach ($updates as $update => $namespace) {
      $this->updates[$update] = new $namespace($this->db, $update, $this->entityManager);
    }

    return $this;
  }

  /**
   * Get all the tasks.
   *
   * @return UpdateBaseInterface[]
   *   All the teaks.
   */
  public function getUpdates() {
    return $this->updates;
  }

  /**
   * Return the update object.
   *
   * @param $update
   *   The update ID.
   *
   * @return UpdateBaseInterface
   *   Update.
   */
  public function get($update) {
    return $this->updates[$update];
  }

  /**
   * Get a list of all the un-processed updates.
   *
   * @return UpdateBaseInterface[]
   *   The list of updates.
   */
  public function getUnProcessedUpdates() {
    // Update that ran before.
    $db_updates = $this->getDbProcessedUpdates();

    // Go over all the updates.
    $updates = [];

    foreach ($this->getUpdates() as $update => $namespace) {
      if (in_array($update, $db_updates)) {
        // Already ran before.
        continue;
      }

      $updates[$update] = new $namespace;
    }

    if (empty($db_updates)) {
      return $updates;
    }

    return $updates;
  }

  /**
   * Get list of processed updates form the DB.
   *
   * @return array
   *   List of processed updates.
   */
  public function getDbProcessedUpdates() {
    return $this->entityManager->get('system')->load('updates')->processed;
  }

  /**
   * Add the processed update to the DB so it won't run again.
   *
   * @param string $name
   *   The name of the update.
   */
  public function addProcessedUpdate($name) {
    /** @var \Nuntius\Entity\System $updates */
    $updates = $this->entityManager->get('system')->load('updates');
    $processed = $updates->processed;
    $processed[] = $name;
    $this->entityManager->get('system')->update('updates', ['processed' => $processed]);
  }

}
