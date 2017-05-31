<?php

namespace Nuntius\Db;

/**
 * Interface for a DB storage controller.
 */
interface DbStorageHandlerInterface {

  /**
   * Set the table name.
   *
   * Before operating on the storage we need to set the DB name.
   *
   * @param $table
   *   The table name.
   *
   * @return \Nuntius\Db\DbStorageHandlerInterface
   */
  public function table($table);

  /**
   * Insert a data into the DB.
   *
   * @param $document
   *   The entry which will be inserted to the DB.
   *
   * @return array
   *   The full entry with the ID in the DB.
   */
  public function save($document);

  /**
   * Load an entry for the DB.
   *
   * @param $id
   *   The ID of the entry.
   *
   * @return array
   *  The entry loaded from the DB.
   */
  public function load($id);

  /**
   * Load multiple entries from the DB.
   *
   * In order to load all the items pass an empty array.
   *
   * @param array $ids
   *   List of IDs.
   *
   * @return array
   *   All the items.
   */
  public function loadMultiple(array $ids = []);

  /**
   * Update an entry in the DB.
   *
   * @param $document
   *   The document to update in the DB.
   *
   * @return array
   *   The new document.
   */
  public function update($document);

  /**
   * Delete an entry from the DB.
   *
   * @param $id
   *   The ID of the document to delete from the DB.
   */
  public function delete($id);

  /**
   * Delete multiple items from the DB.
   *
   * In order to delete all the items pass an empty array.
   *
   * @param array $ids
   *   List of IDs.
   */
  public function deleteMultiple(array $ids = []);

}
