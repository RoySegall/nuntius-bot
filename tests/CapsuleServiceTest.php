<?php

namespace tests;

use Nuntius\Capsule\CapsuleErrorException;
use Nuntius\Capsule\CapsuleService;
use Nuntius\Db\DbDispatcher;

/**
 * Testing event dispatcher.
 */
class CapsuleServiceTest extends TestsAbstract {

  /**
   * @var array
   *
   * List of services.
   */
  protected $services = [
    'capsuleService' => 'capsule_manager',
    'db' => 'db',
  ];

  /**
   * @var CapsuleService
   */
  protected $capsuleService;

  /** @var DbDispatcher */
  protected $db;

  /**
   * Testing the capsule detector.
   */
  public function testGetCapsules() {
    $capsules = $this->capsuleService->getCapsules();

    $this->assertArrayHasKey('system', $capsules);
    $this->assertArrayHasKey('message', $capsules);

    $this->assertEquals([
      'path' => "capsules/core/message",
      'machine_name' => "message",
      'name' => "Message",
      'description' => "Testing integration with the system capsule.",
      'dependencies' => [
        "system"
      ]
    ], $capsules['message']);

    $this->assertEquals([
      'path' => "capsules/core/system",
      'machine_name' => "system",
      'name' => "System",
      'description' => "A base capsule with a lot of core functionality",
    ], $capsules['system']);
  }

  /**
   * Testing capsule enabling.
   */
  public function testEnableCapsule() {
    $this->assertFalse($this->capsuleService->capsuleEnabled('system'));
    $this->capsuleService->enableCapsule('message');
    $this->assertTrue($this->capsuleService->capsuleEnabled('message'));
    $this->assertTrue($this->capsuleService->capsuleEnabled('system'));

  }

  /**
   * Testing capsule disabling.
   */
  public function testDisableCapsule() {
    // Enabling the system capsule.
    $this->capsuleService->enableCapsule('system');

    // Making sure the capsule enabled.
    $this->assertTrue($this->capsuleService->capsuleEnabled('system'));

    // Making sure the capsule can be disabled.
    $this->capsuleService->disableCapsule('system');

    $this->assertFalse($this->capsuleService->capsuleEnabled('system'));
  }

  /**
   * Testing capsule list.
   */
  public function testCapsuleList() {
    $this->assertEquals($this->capsuleService->capsuleList('enabled'), []);

    $this->capsuleService->enableCapsule('message');

    $list = $this->capsuleService->capsuleList('enabled');

    $this->assertTrue(in_array('system', $list));
    $this->assertTrue(in_array('message', $list));

    $this->assertEquals($this->capsuleService->capsuleList('disabled'), []);

    $this->capsuleService->disableCapsule('message');

    $enabled = $this->capsuleService->capsuleList('enabled');
    $disabled = $this->capsuleService->capsuleList('disabled');

    $this->assertTrue(in_array('message', $disabled));
    $this->assertTrue(in_array('system', $enabled));

  }

  /**
   * Testing the capsule detection.
   */
  public function testGetCapsuleImplementations() {
    $this->capsuleService->enableCapsule('message');

    try {
      $this->capsuleService->getCapsuleImplementations('message');
      $this->fail('Exception was not thrown when trying to get capsule with out implementations.');
    }
    catch (CapsuleErrorException $e) {
    }

    $implementations = $this->capsuleService->getCapsuleImplementations('system');

    $this->assertArrayHasKey('services', $implementations);
    $this->assertNotEmpty($this->capsuleService->getCapsuleImplementations('system', 'services'));
  }

  /**
   * Testing capsule exist.
   */
  public function testCapsuleExists() {
    $this->assertTrue($this->capsuleService->capsuleExists('message'));
    $this->assertTrue($this->capsuleService->capsuleExists('system'));
    $this->assertFalse($this->capsuleService->capsuleExists('pizza'));
  }

  /**
   * Testing capsule enabled.
   */
  public function testCapsuleEnabled() {
    $this->assertFalse($this->capsuleService->capsuleEnabled('message'));
    $this->capsuleService->enableCapsule('message');
    $this->assertTrue($this->capsuleService->capsuleEnabled('message'));
    $this->assertTrue($this->capsuleService->capsuleEnabled('system'));
  }

  /**
   * For now, disable.
   */
  public function testAutoloaderRegister() {
    $this->assertFalse(class_exists('\Nuntius\System\PluginDispatcher'));

    $this->capsuleService->enableCapsule('system');

    new \Nuntius\System\PluginDispatcher();
  }

}
