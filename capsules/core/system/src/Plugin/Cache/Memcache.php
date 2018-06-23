<?php

namespace Nuntius\System\Plugin\Cache;

use Nuntius\Nuntius;
use Nuntius\NuntiusConfig;
use Nuntius\System\CacheBase;
use Nuntius\System\Annotations\Cache as cache;
use Nuntius\System\HookContainerInterface;

/**
 * @cache(
 *   id = "memcache",
 * )
 */
class Memcache extends CacheBase implements HookContainerInterface {

  /**
   * @var NuntiusConfig
   */
  protected $config;

  /**
   * @var \Memcached
   */
  protected $memcached;

  /**
   * {@inheritdoc}
   */
  static function getContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container) {
    return new static($container->get('config'));
  }

  /**
   * Memcache constructor.
   *
   * @param NuntiusConfig $config
   */
  public function __construct(NuntiusConfig $config) {
    $this->config = $config;

    $connection = $config->getSetting('memcache');

    $this->memcached = new \Memcached();
    $this->memcached->addServer($connection['host'], $connection['port']);
  }

  /**
   * {@inheritdoc}
   */
  public function install() {
    // Nothing to do here.
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
    return $this->memcached->get($cid);
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

    $old_content = $content;
    $content = serialize($content);

    $this->memcached->set($id, $content, $expires);

    return [
      'id' => $id,
      'content' => $old_content,
      'expires' => $expires,
    ];
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
