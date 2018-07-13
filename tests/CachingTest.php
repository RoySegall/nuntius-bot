<?php

namespace tests;

use Nuntius\Capsule\CapsuleServiceInterface;
use Nuntius\System\CacheBase;
use Nuntius\System\CachePluginManager;
use Nuntius\System\EntityPluginManager;
use Nuntius\System\Plugin\Cache\DBCache;
use Nuntius\System\Plugin\Entity\System;

/**
 * Testing caching issues.
 */
class CachingTest extends TestsAbstract {

  protected $services = [
    'entityManager' => 'entity.plugin_manager',
    'capsuleService' => 'capsule_manager',
    'cacheManager' => 'cache.plugin_manager',
  ];

  protected $capsules = ['system'];

  /**
   * @var EntityPluginManager
   */
  protected $entityManager;

  /**
   * @var CapsuleServiceInterface
   */
  protected $capsuleService;

  /**
   * @var CachePluginManager
   */
  protected $cacheManager;

  /**
   * @return System
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  protected function createSystem() {
    /** @var System $entity */
    $entity = $this->entityManager->createInstance('system');

    $entity->name = 'Testing';
    $entity->machine_name = 'testing';
    $entity->description = 'testing entity';
    $entity->path = '.';
    $entity->status = 1;

    return $entity->save();
  }

  /**
   * Testing the caching mechanisms.
   */
  public function testCache() {
//    $this->cacheTestHelper($this->cacheManager->createInstance('db'));
    $this->cacheTestHelper($this->cacheManager->createInstance('memcache'));
  }

  /**
   * A method for testing how various caches handle the caching flow.
   *
   * @param CacheBase $cache
   *  The cache object.
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  protected function cacheTestHelper(CacheBase $cache) {
    $cid = 'testing_' . time();

    $this->assertFalse($cache->get($cid));
    $cache->set($cid, 'foo', time() + 5);

    $this->assertEquals($cache->get($cid), 'foo');
    sleep(10);
    $this->assertFalse($cache->get($cid), 'foo');

    $system = $this->createSystem();

    $system_cid = 'system_cache_' . time();
    $cache->set($system_cid, $system);

    $this->assertEquals($cache->get($system_cid), $system);
  }

}
