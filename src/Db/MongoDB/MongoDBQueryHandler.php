<?php

namespace Nuntius\Db\MongoDB;

use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;
use Nuntius\Db\DbQueryHandlerInterface;
use Nuntius\Nuntius;

/**
 * MongoDB query handler.
 */
class MongoDBQueryHandler implements DbQueryHandlerInterface {

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
    '=' => '$eq',
    '!=' => '$ne',
    '>' => '$gt',
    '>=' => '$gte',
    '<' => '$lt',
    '<=' => '$lte',
    'CONTAINS' => '$regex',
    'IN' => '$in',
    'NOT_IN' => '$nin'
  ];

  /**
   * @var \MongoDB\Database
   */
  protected $mongo;

  /**
   * Constructing.
   */
  function __construct() {
    $this->mongo = Nuntius::getMongoDB()->getConnection();
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

    // Minor adjustment for MongoDB.
    if ($property == 'id') {
      $property = '_id';
      $value = new ObjectId($value);
    }

    if ($operator == 'CONTAINS') {
      $searchValue = [$this->operators[$operator] => ".*{$value}.*"];
    }
    elseif (in_array($operator, ['IN', 'NOT_IN'])) {
      $searchValue = [$this->operators[$operator] => $value];
    }
    else {
      $searchValue = [$this->operators[$operator] => $value];
    }

    $this->conditions[$property] = $searchValue;
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
   * Set the mode of the changes flag.
   *
   * @param bool $mode
   *   The mode of the flag.
   *
   * @throws \Exception
   */
  public function setChanges($mode = TRUE) {
    throw new \Exception('MongoDB does not support real time feed.');
  }

  /**
   * Return the changes flag.
   *
   * @throws \Exception
   */
  public function getChanges() {
    throw new \Exception('MongoDB does not support real time feed.');
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    /** @var BSONDocument[] $cursor */
    $cursor = $this->mongo->selectCollection($this->table)->find($this->conditions);
    $items = [];

    foreach ($cursor as $doc) {
      // Get the item.
      $item = $doc->getArrayCopy();

      // Order the object.
      $item['id'] = $item['_id']->__toString();
      unset($item['_id']);

      // Add to list.
      $items[] = $item;
    }

    // Clean up the conditions.
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
