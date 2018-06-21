<?php

namespace Nuntius\System\Plugin\Cache;

use Nuntius\Nuntius;
use Nuntius\NuntiusConfig;
use Nuntius\System\CacheBase;
use Nuntius\System\Annotations\Cache as cache;

/**
 * @cache(
 *   id = "memcache",
 * )
 */
class Memcache extends CacheBase {

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
  public function getMultiple($cids) {
  }

  /**
   * {@inheritdoc}
   */
  public function set($id, $content, $expires) {
  }

  /**
   * Checking that the memcache is ready to use.
   *
   * @return bool
   *
   * @throws \Exception
   */
  public static function ready() {
    if (!class_exists('Memcached')) {
      return false;
    }

    /** @var NuntiusConfig $config */
    $config = Nuntius::container()->get('config');

    if (!@$connection = $config->getSetting('memcache')) {
      return false;
    }

    $m = new \Memcached();
    $m->addServer($connection['host'], $connection['port']);

    return $m->getStats();
  }
}
