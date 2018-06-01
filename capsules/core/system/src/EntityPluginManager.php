<?php

namespace Nuntius\System;

use Nuntius\Nuntius;

class EntityPluginManager {

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
   * @return array[]
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function getEntitiesList() {
    return $this->pluginManager->getPlugins('Plugin\Entity', new \Nuntius\System\Annotations\Entity());
  }

  /**
   * @param $id
   * @return mixed
   * @throws \Exception
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function createInstance($id) {
    $list = $this->getEntitiesList();

    if (!in_array($id, array_keys($list))) {
      throw new \Exception('The entity ' . $id . ' does not exists.');
    }

    $plugin_info = $list[$id];

    // Check if the hook need the container or not.
    if (method_exists($plugin_info['namespace'], 'getContainer')) {
      $object = call_user_func(array($plugin_info['namespace'], 'getContainer'), Nuntius::container());
    }
    else {
      $object = new $plugin_info['namespace'];
    }

    foreach ($plugin_info as $key => $value) {
      if ($key == 'namespace') {
        continue;
      }

      $object->{$key} = $value;
    }

    return $object;
  }

}