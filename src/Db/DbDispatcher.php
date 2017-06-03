<?php

namespace Nuntius\Db;

use Nuntius\NuntiusConfig;

/**
 * The DB dispatcher is the one which responsible for getting the DB handler.
 */
class DbDispatcher {

  /**
   * The config service.
   *
   * @var NuntiusConfig
   */
  protected $config;

  /**
   * The DB driver.
   *
   * @var string
   */
  protected $driver;

  /**
   * Dispatching the DB controllers.
   *
   * @param NuntiusConfig $config
   *   The config service.
   */
  function __construct(NuntiusConfig $config) {
    $this->config = $config;
    $this->driver = $this->config->getSetting('db_driver');
  }

  /**
   * Set the driver.
   *
   * @param $driver
   *   The DB driver.
   *
   * @return \Nuntius\Db\DbDispatcher
   *   The current instance.
   */
  public function setDriver($driver) {
    $this->driver = $driver;
    return $this;
  }

  /**
   * Get the DB driver.
   *
   * @return string
   *   The driver of the DB.
   */
  public function getDriver() {
    return $this->driver;
  }

  /**
   * Get the controller for the DB.
   *
   * @param $controller
   *   The type of controller - storage, query etc. etc.
   *
   * @return string
   *   The namespace of the controller.
   *
   * @throws \Exception
   */
  public function getDriverController($controller) {

    if (empty($this->config->getSetting('db_drivers')[$this->driver])) {
      throw new \Exception('The DB driver ' . $this->driver . ' does not exists');
    }

    return $this->config->getSetting('db_drivers')[$this->driver][$controller];
  }

  /**
   * Get the DB query.
   *
   * @return DbQueryHandlerInterface
   */
  public function getQuery() {
    $namespace = $this->getDriverController('query');

    return new $namespace;
  }

  /**
   * Get the DB storage.
   *
   * @return DbStorageHandlerInterface
   */
  public function getStorage() {
    $namespace = $this->getDriverController('storage');

    return new $namespace;
  }

  /**
   * Get the DB operation.
   *
   * @return DbOperationHandlerInterface
   */
  public function getOperations() {
    $namespace = $this->getDriverController('operations');

    return new $namespace;
  }

  /**
   * Get the DB metadata.
   *
   * @return DbMetadataHandlerInterface
   */
  public function getMetadata() {
    $namespace = $this->getDriverController('metadata');

    return new $namespace;
  }

}
