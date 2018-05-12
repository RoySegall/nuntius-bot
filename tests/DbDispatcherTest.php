<?php

namespace tests;

use Nuntius\Db\DbQueryHandlerInterface;
use Nuntius\Db\DbStorageHandlerInterface;
use Nuntius\Db\MongoDB\MongoDBOperationHandler;
use Nuntius\Db\RethinkDB\RethinkDbOperationHandler;
use Nuntius\Nuntius;

/**
 * Testing DB dispatching.
 */
class DbDispatcherTest extends TestsAbstract {

  /**
   * Testing the config DB controllers services.
   */
  public function testControllersInterfaces() {
    $db = Nuntius::getDb();

    foreach (array_keys(Nuntius::getSettings()->getSetting('db_drivers')) as $driver) {
      $db->setDriver($driver);

      $this->assertInstanceOf('\Nuntius\Db\DbQueryHandlerInterface', $db->getQuery());
      $this->assertInstanceOf('\Nuntius\Db\DbStorageHandlerInterface', $db->getStorage());
      $this->assertInstanceOf('\Nuntius\Db\DbOperationHandlerInterface', $db->getOperations());
      $this->assertInstanceOf('\Nuntius\Db\DbMetadataHandlerInterface', $db->getMetadata());
    }

  }

  /**
   * Testing what happens with un un valid diver.
   */
  public function testUnValidDriver() {
    $driver = Nuntius::getSettings()->getSetting('db_driver');
    // Save that for later.
    try {
      Nuntius::getDb()->setDriver('foo')->getMetadata();
      $this->fail();
    }
    catch (\Exception $e) {
      $this->assertEquals($e->getMessage(), 'The DB driver foo does not exists');
    }

    // Set the default just in case.
    Nuntius::getDb()->setDriver($driver);
  }

  /**
   * Testing the metadata controller.
   */
  public function testMetadata() {
    $dbs = [
      'rethinkdb' => [
        'dbType' => 'NoSQL',
        'installerDescription' => 'No SQL light weight DB with real time support.',
        'supportRealTime' => TRUE,
      ],
      'mongodb' => [
        'dbType' => 'NoSQL',
        'installerDescription' => 'No SQL light weight DB',
        'supportRealTime' => FALSE,
      ],
    ];

    $db = Nuntius::getDb();
    $db_data = $dbs[$db->getDriver()];

    foreach ($db_data as $method => $value) {
      $this->assertEquals($db->getMetadata()->{$method}(), $value);
    }
  }

  /**
   * Testing the query controller.
   */
  public function testQuery() {

    list($db) = $this->generateObjects();

    // Create a random table.
    if (!$db->getOperations()->tableExists('superheroes')) {
      $db->getOperations()->tableCreate('superheroes');
    }

    // Start querying the DB.
    $this->queryingTesting($db->getQuery());

    // Delete the table.
    $db->getOperations()->tableDrop('superheroes');
  }

  /**
   * Testing all the available operators on.
   *
   * @param DbQueryHandlerInterface $query
   *   The query object.
   */
  protected function queryingTesting(DbQueryHandlerInterface $query) {
    // Testing simple operators.
    $this->assertCount(3, $query->table('superheroes')->execute());
    $this->assertCount(1, $query->table('superheroes')->condition('name', 'Tony')->execute());
    $this->assertCount(2, $query->table('superheroes')->condition('name', 'Tony', '!=')->execute());
    $this->assertCount(2, $query->table('superheroes')->condition('age', 18, '>')->execute());
    $this->assertCount(3, $query->table('superheroes')->condition('age', 18, '>=')->execute());
    $this->assertCount(0, $query->table('superheroes')->condition('age', 18, '<')->execute());
    $this->assertCount(2, $query->table('superheroes')->condition('age', 20, '<=')->execute());
    $this->assertCount(1, $query->table('superheroes')->condition('alterego', 'America', 'CONTAINS')->execute());
    $this->assertCount(2, $query->table('superheroes')->condition('alterego', ['Captain America', 'SpiderMan'], 'IN')->execute());

    // Testing complex querying.
    $this->assertCount(1, $query->table('superheroes')->condition('name', 'Tony')->condition('age', 27)->execute());
    $this->assertCount(0, $query->table('superheroes')->condition('name', 'Tony')->condition('age', 90)->execute());
    $this->assertCount(2, $query->table('superheroes')->condition('name', 'Tony', '!=')->condition('age', 25, '<')->execute());
    $this->assertCount(1, $query->table('superheroes')->condition('name', ['Tony'], 'IN')->condition('age', 18, '>')->execute());
    $this->assertCount(3, $query->table('superheroes')->condition('name', ['Peter', 'Tony', 'Steve'], 'IN')->condition('age', 18, '>=')->execute());
    $this->assertCount(2, $query->table('superheroes')->condition('name', ['Peter', 'Tony', 'Steve'], 'IN')->condition('age', 18, '>')->execute());
    $this->assertCount(1, $query->table('superheroes')->condition('name', ['Peter', 'Tony', 'Steve'], 'IN')->condition('age', 20, '<')->execute());
  }

  /**
   * Testing the operation on the DB.
   */
  public function testOperation() {
    $db = Nuntius::getDb();

    // Testing DB related operations.
    $operations = $db->getOperations();
    $operations->dbCreate('testing_db');
    $this->assertTrue($operations->dbExists('testing_db'));
    $operations->dbDrop('testing_db');
    $this->assertFalse($operations->dbExists('testing_db'));

    // Testing table related operations.
    $operations->tableCreate('testing_table');
    $this->assertTrue($operations->tableExists('testing_table'));
    $operations->tableDrop('testing_table');
    $this->assertFalse($operations->tableExists('testing_table'));

    $operations->tableCreate('testing_table');
    $func_name = 'indexTestingDb' . $db->getDriver();
    $this->{$func_name}($operations);
  }

  /**
   * Helping method for testing operation for MongoDB.
   *
   * @param MongoDBOperationHandler $operations
   *   The MongoDB operation handler.
   */
  protected function indexTestingDbmongodb(MongoDBOperationHandler $operations) {
    $operations->indexCreate('testing_table', ['index' => 1]);
    $this->assertTrue($operations->indexExists('testing_table', 'index_1'));
    $operations->indexDrop('testing_table', 'index_1');
    $this->assertFalse($operations->indexExists('testing_table', 'index_1'));
    $operations->tableDrop('testing_table');
  }

  /**
   * Helping method for testing operation for RethinkDB.
   *
   * @param RethinkDbOperationHandler $operations
   *   The RethinkDB operation handler.
   */
  protected function indexTestingDbrethinkdb(RethinkDbOperationHandler $operations) {
    $operations->indexCreate('testing_table', 'index');
    $this->assertTrue($operations->indexExists('testing_table', 'index'));
    $operations->indexDrop('testing_table', 'index');
    $this->assertFalse($operations->indexExists('testing_table', 'index'));
    $operations->tableDrop('testing_table');
  }

  /**
   * @return array
   */
  protected function generateObjects() {
    $objects = [
      ['name' => 'Tony', 'age' => 27, 'alterego' => 'Iron Man'],
      ['name' => 'Peter', 'age' => 20, 'alterego' => 'SpiderMan'],
      ['name' => 'Steve', 'age' => 18, 'alterego' => 'Captain America'],
    ];

    $db = Nuntius::getDb();

    // Create a random table.
    if (!$db->getOperations()->tableExists('superheroes')) {
      $db->getOperations()->tableCreate('superheroes');
    }

    $new_objects = [];

    foreach ($objects as $object) {
      $new_objects[] = $db->getStorage()->table('superheroes')->save($object);
    }

    return [$db, $new_objects];
  }

  /**
   * Testing storage handlers.
   */
  public function testStorage() {
    list($db, $new_objects) = $this->generateObjects();

    // Verify the objects have ids.
    foreach ($new_objects as $new_object) {
      $this->assertArrayHasKey('id', $new_object);
      $this->assertArrayNotHasKey('id', $new_objects);
    }

    // Set up some stuff.
    $id = $new_objects[0]['id'];

    /**
     * @return DbStorageHandlerInterface
     */
    $getTable = function() use($db) {
      return $db->getStorage()->table('superheroes');
    };


    // Verify we can load.
    $object = $getTable()->load($id);
    $this->assertEquals($object['name'], 'Tony');


    // Verify we can update.
    $object['name'] = 'Clark';

    $getTable()->update($object);
    $object = $getTable()->load($id);
    $this->assertEquals($object['name'], 'Clark');

    // Verify we can delete.
    $getTable()->delete($id);
    $this->assertFalse($getTable()->load($id));

    $db->getOperations()->tableDrop('superheroes');
  }

}
