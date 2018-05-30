<?php

namespace Nuntius\System;

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

}