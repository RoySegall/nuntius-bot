<?php

namespace tests;

use Nuntius\Capsule\CapsuleServiceInterface;
use Nuntius\CapsuleTestMain\Annotations\Name;
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
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   * @throws \Doctrine\Common\Annotations\AnnotationException
   * @throws \ReflectionException
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
   *
   * @throws \Doctrine\Common\Annotations\AnnotationException
   * @throws \Nuntius\Capsule\CapsuleErrorException
   * @throws \ReflectionException
   */
  public function testCapsuleMainPlugin() {
    $this->capsuleService->enableCapsule('capsule_test_main');
    $this->assertEquals(array_keys($this->pluginManager->getPlugins('Plugin\Names', new Name())), ['tom']);


    $this->capsuleService->enableCapsule('capsule_test_secondary');
    $this->assertTrue(in_array('hal', array_keys($this->pluginManager->getPlugins('Plugin\Names', new Name()))));
  }

}
