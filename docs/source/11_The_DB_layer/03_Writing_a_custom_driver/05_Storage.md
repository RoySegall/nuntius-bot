The storage part handles the CRUD operation on tables in the DB. Let's look on
RethinkDB implementation:
```php
<?php

/**
 * RethinkDB storage handler.
 */
class RethinkDbStorageHandler implements DbStorageHandlerInterface {

  /**
   * Constructing.
   */
  function __construct() {
    $this->rethinkdb = Nuntius::getRethinkDB();
    $this->connection = $this->rethinkdb->getConnection();
  }

  /**
   * {@inheritdoc}
   */
  public function table($table) {
    $this->table = $table;
    return $this;
  }

  /**
   * Get the table handler.
   *
   * @return \r\Queries\Tables\Table
   */
  public function getTable() {
    return \r\db(Nuntius::getSettings()->getSetting('rethinkdb')['db'])
      ->table($this->table);
  }

  /**
   * {@inheritdoc}
   */
  public function save($document) {

    if (!isset($document['time'])) {
      $document['time'] = time();
    }

    $result = $this->getTable()->insert($document)->run($this->connection)->getArrayCopy();

    if (!isset($document['id'])) {
      $document['id'] = isset($result['generated_keys']) ? reset($result['generated_keys']) : $result['id'];
    }

    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function load($id) {
    $items = $this->loadMultiple(array($id));

    return reset($items);
  }

  /**
   * {@inheritdoc}
   */
  public function loadMultiple(array $ids = []) {
    $query = Nuntius::getDb()->getQuery()
      ->table($this->table);

    if ($ids) {
      $query->condition('id', $ids, 'IN');
    }

    return $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function update($document) {
    $this->getTable()->get($document['id'])->update($document)->run($this->connection);
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
    $query = $this->getTable();

    if ($ids) {
      $query->getAll(\r\args($ids));
    }

    $query->delete()->run($this->connection);
  }

}
```

Let's have a quick look on the methods:

`save()`: Inserting information into the DB.

`load()`: Loading specific ID from the DB.

`loadMultiple()`: Loading couple or all the items from the DB(when no IDs was 
passed).

`update()`: Update a given object in the DB. The passed object must contain the
ID of the entry.

`delete()`: Deleting a specific item form the DB.

`deleteMultiple()`: Similar to `loadMultiple` but for deleting - if not IDs was
passed then deleting all the entries in the table.
