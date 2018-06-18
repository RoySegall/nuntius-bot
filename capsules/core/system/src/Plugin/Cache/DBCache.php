<?php

namespace Nuntius\System\Plugin\Cache;

use Nuntius\System\CacheBase;
use Nuntius\System\Annotations\Cache as cache;

/**
 * @cache(
 *   id = "db",
 * )
 */
class DBCache extends CacheBase {

  /**
   * {@inheritdoc}
   */
  public function install() {
  }

  /**
   * {@inheritdoc}
   */
  public function clear($cid = NULL) {
  }

  /**
   * Getting the cache from the DB.
   *
   * @param $cid
   *  The cache id.
   *
   * @return mixed
   */
  public function get($cid) {
  }

  /**
   * {@inheritdoc}
   */
  public function getMultiple() {
  }

  /**
   * {@inheritdoc}
   */
  public function set($id, $content, $expires) {
  }
}
