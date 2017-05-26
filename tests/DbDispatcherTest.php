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
    $this->assertInstanceOf('\Nuntius\Db\DbQueryHandlerInterface', Nuntius::getDb()->getQuery());
    $this->assertInstanceOf('\Nuntius\Db\DbStorageHandlerInterface', Nuntius::getDb()->getStorage());
    $this->assertInstanceOf('\Nuntius\Db\DbOperationHandlerInterface', Nuntius::getDb()->getOperations());
    $this->assertInstanceOf('\Nuntius\Db\DbMetadataHandlerInterface', Nuntius::getDb()->getMetadata());
  }

}
