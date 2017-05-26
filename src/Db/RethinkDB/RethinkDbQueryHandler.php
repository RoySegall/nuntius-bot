<?php

namespace Nuntius\Db\RethinkDB;

use Nuntius\Db\DbQueryHandlerInterface;
use Nuntius\Nuntius;

/**
 * RethinkDB query handler.
 */
class RethinkDbQueryHandler implements DbQueryHandlerInterface {

  /**
   * RethinkDB service.
   *
   * @var \Nuntius\NuntiusRethinkdb
   */
  protected $rethinkDB;

  /**
   * The table name.
   *
   * @var string
   */
  protected $table;

  /**
   * List of conditions.
   *
   * @var array
   */
  protected $conditions = [];

  /**
   * Constructing the query.
   */
  function __construct() {
    $this->rethinkDB = Nuntius::getRethinkDB();
  }

  /**
   * {@inheritdoc}
   */
  public function table($table) {
    $this->table = $table;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function condition($property, $value, $operator = '=') {
    $this->conditions[] = [
      'property' => $property,
      'value' => $value,
      'operator' => $operator,
    ];

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {

  }

}
