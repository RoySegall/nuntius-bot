<?php

namespace Nuntius\Db\RethinkDB;

use Nuntius\Db\DbQueryHandlerInterface;
use Nuntius\Nuntius;
use r\Exceptions\RqlException;
use r\ValuedQuery\RVar;

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
   * Holds information for the pager of the query.
   *
   * @var array
   */
  protected $range = [];

  /**
   * Keep the sort settings.
   *
   * @var array
   */
  protected $sort = [];

  /**
   * @var array
   *
   * Keep the allowed operators on the query.
   */
  protected $operators = [
    '=' => 'eq',
    '!=' => 'ne',
    '>' => 'gt',
    '>=' => 'ge',
    '<' => 'lt',
    '<=' => 'le',
    'CONTAINS' => 'match',
    'IN' => 'args',
  ];

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
  public function pager($start, $length) {
    $this->range = [
      'start' => $start,
      'length' => $length,
    ];

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function orderBy($field, $direction) {
    $this->sort[] = [
      'field' => $field,
      'direction' => $direction,
    ];
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $query = \r\table($this->table);

    if ($this->conditions) {
      foreach ($this->conditions as $condition) {

        $operator = !empty($condition['operator']) ? $condition['operator'] : '=';
        if (!in_array($operator, array_keys($this->operators))) {
          throw new RqlException("The operator {$operator} does not allowed. Only " . implode(', ', array_keys($this->operators)));
        }
        if ($operator == 'IN') {
          $row = function(RVar $doc) use ($condition) {
            return \r\expr($condition['value'])->contains($doc->getField($condition['property']));
          };
        }
        else {
          $row = \r\row($condition['property'])->{$this->operators[$operator]}($condition['value']);
        }

        $query = $query->filter($row);
      }
    }

    if ($this->range) {
      $query = $query->slice($this->range['start'], $this->range['length']);
    }

    if ($this->sort) {
      foreach ($this->sort as $sort) {
        $sort['field'] = empty($sort['field']) ? 'id' : $sort['field'];
        $sort_object = $sort['direction'] == 'ASC' ? \r\asc($sort['field']) : \r\desc($sort['field']);
        $query = $query->orderBy($sort_object);
      }
    }

    $items = [];

    foreach ($query->run($this->rethinkDB->getConnection()) as $item) {
      $array_copy = $item->getArrayCopy();
      $items[] = $array_copy['id'];
    }

    $this->cleanUp();

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function cleanUp() {
    $this->table = '';
    $this->conditions = [];
    $this->sort = [];
    $this->range = [];
  }

}
