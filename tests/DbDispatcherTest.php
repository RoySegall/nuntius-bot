<?php

namespace tests;
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
    try {
      Nuntius::getDb()->setDriver('foo')->getMetadata();
      $this->fail();
    }
    catch (\Exception $e) {
      $this->assertEquals($e->getMessage(), 'The DB driver foo does not exists');
    }
  }

  /**
   * Testing the metadata.
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

}
