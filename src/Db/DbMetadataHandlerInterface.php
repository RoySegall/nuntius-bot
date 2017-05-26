<?php

namespace Nuntius\Db;

/**
 * Interface for a DB metadata controller.
 */
interface DbMetadataHandlerInterface {

  /**
   * return the DB type - SQL or NoSQL
   *
   * @return string
   *   The DB type.
   */
  public function DbType();

  /**
   * Return a small description when choosing the DB at the install phase.
   *
   * @return string
   *   A small description about the DB.
   */
  public function installerDescription();

  /**
   * Return true or false of the DB support real time.
   *
   * We need to know this for the live view task.
   *
   * @return bool
   */
  public function supportRealTime();

}
