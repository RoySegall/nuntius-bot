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
   * Constructing the update manager.
   *
   * @param array $updates
   *   List of all the updates.
   */
  function __construct($updates) {
    $db = Nuntius::getRethinkDB();
    $entity_manager = Nuntius::getEntityManager();

    foreach ($updates as $update => $namespace) {
      $this->updates[$update] = new $namespace($db, $update, $entity_manager);
    }
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
   * Get all the tasks.
   *
   * @return UpdateBaseInterface[]
   *   All the teaks.
   */
  public function getUpdates() {
    return $this->updates;
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
    return Nuntius::getEntityManager()->get('system')->load('updates')->processed;
  }

  /**
   * Add the processed update to the DB so it won't run again.
   *
   * @param string $name
   *   The name of the update.
   */
  public function addProcessedUpdate($name) {
    /** @var \Nuntius\Entity\System $updates */
    $updates = Nuntius::getEntityManager()->get('system')->load('updates');
    $processed = $updates->processed;
    $processed[] = $name;
    Nuntius::getEntityManager()->get('system')->update('updates', ['processed' => $processed]);
  }

}
