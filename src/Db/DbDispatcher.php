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
   * Dispatching the DB controllers.
   *
   * @param NuntiusConfig $config
   *   The config service.
   */
  function __construct(NuntiusConfig $config) {
    $this->config = $config;
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
    $driver = $this->config->getSetting('db_driver');

    if (!$controllers = $this->config->getSetting('db_drivers')[$driver]) {
      throw new \Exception('The DB driver ' . $driver . ' does not exists');
    }

    return $controllers[$controller];
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
