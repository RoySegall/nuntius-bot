<?php

namespace Nuntius;

/**
 * Interface EntityBaseInterface.
 */
interface EntityBaseInterface {

  /**
   * Load a single entities.
   *
   * @param $id
   *   Entity ID.
   *
   * @return $this
   *   The entity.
   */
  public function load($id);

  /**
   * Load multiple entities from the DB.
   *
   * @param array $ids
   *   List of entity IDs.
   *
   * @return EntityBaseInterface[]
   */
  public function loadMultiple(array $ids = []);

  /**
   * Inert an entry to the DB.
   *
   * @param array $item
   *   The item to insert into the DB.
   */
  public function save(array $item);

  /**
   * Delete an entry from the DB.
   *
   * @param $id
   *   The entity ID.
   */
  public function delete($id);

  /**
   * Updating an entry in the DB.
   *
   * @param $data
   *   The data to update.
   */
  public function update($data);

}
