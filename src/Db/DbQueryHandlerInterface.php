<?php

namespace Nuntius\Db;

/**
 * Interface for a DB query controller.
 */
interface DbQueryHandlerInterface {

  /**
   * Set the table name.
   *
   * @param $table
   *   The table name.
   *
   * @return \Nuntius\Db\DbQueryHandlerInterface
   */
  public function table($table);

  /**
   * Set the conditions of the query.
   *
   * @param $property
   *   The field.
   * @param $value
   *   The value.
   * @param $operator
   *   The operator: <, >, =>, >=, !=, =, IN, NOT_IN. Default set to =.
   *
   * @return DbQueryHandlerInterface
   */
  public function condition($property, $value, $operator = '=');

  /**
   * Execute the query.
   *
   * @return array
   *   List of IDs.
   */
  public function execute();

}
