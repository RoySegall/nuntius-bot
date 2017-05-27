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
  public function _testControllersInterfaces() {
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
  public function _testUnValidDriver() {
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
  public function _testMetadata() {
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
      ['name' => 'Tony', '≈' => 27, 'alterego' => 'Iron Man'],
      ['name' => 'Peter', 'age' => 20, 'alterego' => 'SpiderMan'],
      ['mame' => 'Steve', 'age' => 18, 'alterego' => 'Captain America'],
    ];

    // Create a random table.
    Nuntius::getRethinkDB()->createTable('superheroes');

    // Create the objects.
    Nuntius::getRethinkDB()->addEntry('superheroes', $objects);

    // Start querying the DB.
    $db = Nuntius::getDb();

    $db->setDriver('rethinkdb');

    $this->queryingTesting($db->getQuery());

    // Delete the table.
    Nuntius::getRethinkDB()->deleteTable('superheroes');
  }

  protected function queryingTesting(DbQueryHandlerInterface $query) {
    $this->assertCount(3, $query->table('superheroes')->execute());
    $this->assertCount(1, $query->table('superheroes')->condition('name', 'Tony')->execute());
    $this->assertCount(2, $query->table('superheroes')->condition('name', 'Tony', '!=')->execute());
  }

}
