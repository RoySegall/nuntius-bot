<?php

namespace tests;

use Nuntius\Capsule\CapsuleServiceInterface;
use Nuntius\System\Annotations\Entity;
use Nuntius\System\PluginManager;

class PluginManagerTest extends TestsAbstract {

  protected $services = [
    'pluginManager' => 'plugin_manager',
    'capsuleService' => 'capsule_manager',
  ];

  protected $capsules = ['system'];

  /**
   * @var PluginManager
   */
  protected $pluginManager;

  /**
   * @var CapsuleServiceInterface
   */
  protected $capsuleService;


  /**
   * Testing the plugin manager.
   */
  public function testPluginManager() {
    // Get all the plugins.
    $plugins = $this->pluginManager->getPlugins('Plugin\Entity', new Entity());

    $this->assertTrue(in_array('user', array_keys($plugins)));
    $this->assertTrue(in_array('system', array_keys($plugins)));

    $this->capsuleService->enableCapsule('capsule_test_main');
    $plugins = $this->pluginManager->getPlugins('Plugin\Entity', new Entity());

    $this->assertTrue(in_array('user', array_keys($plugins)));
    $this->assertTrue(in_array('system', array_keys($plugins)));
    $this->assertTrue(in_array('tag', array_keys($plugins)));
    $this->assertTrue(in_array('system', array_keys($plugins)));
  }

  /**
   * Testing plugin by another module.
   */
  public function testCapsuleMainPlugin() {
    // Enabling the capsule test main capsule.
    $this->capsuleService->enableCapsule('capsule_test_main');
  }

}
