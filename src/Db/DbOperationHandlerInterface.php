<?php

namespace Nuntius\Db;

/**
 * Interface for a DB operations controller.
 */
interface DbOperationHandlerInterface {

  /**
   * Check if the connection to the DB is OK.
   *
   * @return bool
   */
  public function connected();

  /**
   * Get the DB errors, if any.
   *
   * @return string
   */
  public function getError();

  /**
   * Create the DB.
   *
   * @param string $db
   *
   * @return \Nuntius\Db\DbOperationHandlerInterface
   *   The current instance.
   */
  public function dbCreate($db);

  /**
   * Deleting the DB.
   *
   * @param string $db
   *   The DB name.
   *
   * @return \Nuntius\Db\DbOperationHandlerInterface
   *   The current instance.
   */
  public function dbDrop($db);

  /**
   * Return all the list of the DBs.
   *
   * @return array
   */
  public function dbList();

  /**
   * Check if the DB exists.
   *
   * @param string $db
   *   The DB name.
   *
   * @return bool
   */
  public function dbExists($db);

  /**
   * Create a table.
   *
   * @param string $table
   *   The table name.
   *
   * @return \Nuntius\Db\DbOperationHandlerInterface
   *   The current instance.
   */
  public function tableCreate($table);

  /**
   * Delete the table.
   *
   * @param string $table
   *   The table name.
   *
   * @return \Nuntius\Db\DbOperationHandlerInterface
   *   The current instance.
   */
  public function tableDrop($table);

  /**
   * Return list of tables.
   *
   * @return array
   */
  public function tableList();

  /**
   * Check if the table exists.
   *
   * @param string $table
   *   The table name.
   *
   * @return bool
   */
  public function tableExists($table);

  /**
   * Create an index in a table.
   *
   * @param string $table
   *   The table name.
   * @param string $column
   *   The column name.
   *
   * @return \Nuntius\Db\DbOperationHandlerInterface
   *   The current instance.
   */
  public function indexCreate($table, $column);

  /**
   * Drop the index.
   *
   * @param string $table
   *   The table name.
   * @param string $column
   *   The column name.
   *
   * @return \Nuntius\Db\DbOperationHandlerInterface
   *   The current instance.
   */
  public function indexDrop($table, $column);

  /**
   * Get all the index in a table.
   *
   * @param string $table
   *   The table name.
   *
   * @return array
   *   The list of the indexes.
   */
  public function indexList($table);

}
