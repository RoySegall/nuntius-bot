<?php

namespace Nuntius\System;

use Nuntius\Nuntius;

class CachePluginManager {

  /**
   * @var PluginManager
   */
  protected $pluginManager;

  /**
   * EntityPluginManager constructor.
   * @param PluginManager $plugin_manager
   */
  public function __construct(PluginManager $plugin_manager) {
    $this->pluginManager = $plugin_manager;
  }

  /**
   * @return CacheBase[]
   *
   * @throws \Doctrine\Common\Annotations\AnnotationException
   * @throws \Nuntius\Capsule\CapsuleErrorException
   * @throws \ReflectionException
   */
  public function getCacheList() {
    /** @var CacheBase[] $caches */
    $caches = $this->pluginManager->getPlugins('Plugin\Cache', new \Nuntius\System\Annotations\Cache());

    return array_filter($caches, function($item) {
      return call_user_func([$item['namespace'], 'ready']);
    });
  }

  /**
   * @param $id
   *
   * @return mixed
   * @throws \Exception
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function createInstance($id) {
    $list = $this->getCacheList();

    if (!in_array($id, array_keys($list))) {
      throw new \Exception('The cache plugin ' . $id . ' does not exists or it is not ready to use.');
    }

    // todo: Move to trait or something.
    $plugin_info = $list[$id];

    // Check if the hook need the container or not.
    if (method_exists($plugin_info['namespace'], 'getContainer')) {
      $object = call_user_func(array($plugin_info['namespace'], 'getContainer'), Nuntius::container());
    }
    else {
      $object = new $plugin_info['namespace'];
    }

    foreach ($plugin_info as $key => $value) {

      if ($key == 'id') {
        // We don't want to override the record ID.
        $key = 'plugin_id';
      }

      $object->{$key} = $value;
    }

    return $object;
  }

}
