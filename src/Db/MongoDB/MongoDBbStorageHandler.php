<?php

namespace Nuntius\Db\MongoDB;

use MongoDB\Model\BSONDocument;
use Nuntius\Db\DbStorageHandlerInterface;
use Nuntius\Nuntius;

/**
 * MongoDB storage handler.
 */
class MongoDBbStorageHandler implements DbStorageHandlerInterface {

  /**
   * The table name.
   *
   * @var string
   */
  protected $table;

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
  public function save($document) {

    if (!isset($document['time'])) {
      $document['time'] = time();
    }

    $result = $this->mongo->selectCollection($this->table)->insertOne($document);

    $id = $result->getInsertedId();

    if (!empty($document['id'])) {
      if (self::isMongoIdObject($document['id'])) {
        $document['id'] = $id->__toString();
      }
    }
    else {
      $document['id'] = $id->__toString();
    }

    $document['_id'] = $id->__toString();

    // Setting the _id as the id thus ensure we can load later on.
    $this->update($document);
    unset($document['_id']);

    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function load($id) {
    $items = $this->loadMultiple([$id]);

    return reset($items);
  }

  /**
   * {@inheritdoc}
   */
  public function loadMultiple(array $ids = []) {
    $query = $this->mongo->selectCollection($this->table);

    $filter = [];

    if ($ids) {
      $filter['id'] = ['$in' => $ids];
    }

    /** @var BSONDocument[] $cursor */
    $cursor = $query->find($filter);

    $results = [];

    foreach ($cursor as $doc) {
      // Get the item.
      $item = $doc->getArrayCopy();

      // Order the object.
      unset($item['_id']);

      // Add to list.
      $results[] = $item;
    }

    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function update($document) {

    if (empty($document['_id'])) {
      // We don't have an _id. Looking for entities with id to update.
      $filter = ['id' => $document['id']];
    }
    else {
      // When creating a new entity we need to update the id with the _id
      // property. That's will ensure that we can look for entities using the id
      // property and not _id when using mongo and id when using other DB.
      $filter = ['_id' => new \MongoDB\BSON\ObjectId($document['_id'])];
      unset($document['_id']);
    }

    $this->mongo->selectCollection($this->table)->updateOne(
      $filter,
      ['$set' => $document]
    );

    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function delete($id) {
    $this->deleteMultiple([$id]);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteMultiple(array $ids = []) {
    $this->mongo
      ->selectCollection($this->table)
      ->deleteMany(['id' => [
        '$in' => $ids,
        ]
      ]);
  }

  /**
   * Alias for a function with complex name.
   *
   * @param $id
   *  The ID we ned to check if it a MongoDB ID or not.
   *
   * @return bool
   */
  public static function isMongoIdObject($id) {
    return ctype_xdigit($id);
  }

}
