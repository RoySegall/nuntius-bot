<?php

namespace tests;
use Nuntius\Db\DbQueryHandlerInterface;
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
    // Save that for later.
    try {
      Nuntius::getDb()->setDriver('foo')->getMetadata();
      $this->fail();
    }
    catch (\Exception $e) {
      $this->assertEquals($e->getMessage(), 'The DB driver foo does not exists');
    }
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
    ];

    $db = Nuntius::getDb();

    foreach ($dbs as $driver => $info) {
      $db->setDriver($driver);

      foreach ($info as $method => $value) {
        $this->assertEquals($db->getMetadata()->{$method}(), $value);
      }
    }
  }

  /**
   * Testing the query controller.
   */
  public function testQuery() {
    // Create a list of entries.
    $objects = [
      ['name' => 'Tony', 'age' => 27, 'alterego' => 'Iron Man'],
      ['name' => 'Peter', 'age' => 20, 'alterego' => 'SpiderMan'],
      ['name' => 'Steve', 'age' => 18, 'alterego' => 'Captain America'],
    ];

    $db = Nuntius::getDb();

    // Create a random table.
    $db->getOperations()->tableCreate('superheroes');

    // Create the objects.
    $db->getStorage()->table('superheroes')->save($objects);

    // Start querying the DB.

    $db->setDriver('rethinkdb');

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

    $db->setDriver('rethinkdb');

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

    // Testing index related operations.
    $operations->tableCreate('testing_table');
    $operations->indexCreate('testing_table', 'index');
    $this->assertTrue($operations->indexExists('testing_table', 'index'));
    $operations->indexDrop('testing_table', 'index');
    $this->assertFalse($operations->indexExists('testing_table', 'index'));
    $operations->tableDrop('testing_table');
  }

}
