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
   * @return \Nuntius\Db\DbQueryHandlerInterface
   */
  public function condition($property, $value, $operator = '=');

  /**
   * Set a pager to the query.
   *
   * @param $start
   *   The index of the pager.
   * @param $length
   *   How many items to pull.
   *
   * @return \Nuntius\Db\DbQueryHandlerInterface
   */
  public function pager($start, $length);

  /**
   * Order the query by a given field.s
   *
   * @param $field
   *   The field name.
   * @param $direction
   *   The direction - ASC or DESC
   *
   * @return \Nuntius\Db\DbQueryHandlerInterface
   */
  public function orderBy($field, $direction);

  /**
   * Execute the query.
   *
   * @return array
   *   List of IDs.
   */
  public function execute();

}
