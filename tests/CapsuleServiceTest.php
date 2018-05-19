<?php

namespace tests;

use Nuntius\Capsule\CapsuleService;
use Nuntius\Examples\Names\NameEvent;
use Nuntius\Nuntius;

/**
 * Testing event dispatcher.
 */
class CapsuleServiceTest extends TestsAbstract
{

  protected $services = [
    'CapsuleService' => 'capsule_manager',
  ];

  /**
   * @var CapsuleService
   */
  protected $CapsuleService;

  /**
   * {@inheritdoc}
   */
  public function setUp()
  {
    parent::setUp();

    $this->CapsuleService->setRoot(getcwd() . '/../');
  }

  /**
   * Testing the capsule detector.
   */
  public function testGetCapsules() {
    $capsules = $this->CapsuleService->getCapsules();

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
      'machine_name' => "sjystem",
      'name' => "System",
      'description' => "A base capsule with a lot of core functionality",
    ], $capsules['system']);
  }

  /**
   * Testing capsule enabling.
   */
  public function testEnableCapsule() {

  }

  /**
   * Testing capsule disabling.
   */
  public function testDisableCapsule() {

  }

  /**
   * Testing capsule list.
   */
  public function testCapsuleList() {

  }

  /**
   * Testing the capsule detection.
   */
  public function testGetCapsuleImplementations() {

  }

  /**
   * Testing capsule exist.
   */
  public function testCapsuleExists() {

  }

  /**
   * Testing capsule enabled.
   */
  public function testCapsuleEnabled() {

  }

}
