<?php

namespace Nuntius\System;

/**
 * Class CacheBase
 *
 * @package Nuntius\System
 */
abstract class CacheBase {

  public $plugin_id;

  /**
   * Prepare the environment by the need of the plugin.
   *
   * Examples:
   *  * Install a record in the DB
   *  * Set up the connection for a remote DB.
   *
   * @return mixed
   */
  abstract public function install();

  /**
   * Clear a cache by the cid.
   *
   * @param string $cid
   *  The cache ID.
   *
   * @return mixed
   */
  abstract public function clear($cid = NULL);

  /**
   * Getting the cache from the DB.
   *
   * @param $cid
   *  The cache id.
   *
   * @return mixed
   */
  abstract public function get($cid);

  /**
   * Get all the caches in the current cache bin.
   *
   * @return mixed
   */
  abstract public function getMultiple();

  /**
   * Setting the cache.
   *
   * @param $id
   *  The cache ID.
   * @param $content
   *  The content of the cache.s
   * @param int $expires
   *  When the cache is not longer valid.
   *
   * @return mixed
   */
  abstract public function set($id, $content, $expires);

}
