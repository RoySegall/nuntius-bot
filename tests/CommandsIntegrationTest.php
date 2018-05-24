<?php

namespace tests;

use Nuntius\Capsule\CapsuleService;
use Nuntius\Db\DbDispatcher;
use Nuntius\Nuntius;

/**
 * Testing event dispatcher.
 */
class CommandsIntegrationTest extends TestsAbstract {

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

  /**
   * @var DbDispatcher
   */
  protected $db;

  /**
   * Testing the commands integration with other modules.
   *
   * @throws \Exception
   */
  public function testCommandsIntegrations() {
    // First we need to enable the system capsule.
    $this->capsuleService->enableCapsule('system');

    // Reset the container for refresh the services.
    Nuntius::container(TRUE);

    $application = new \Symfony\Component\Console\Application();
    \Nuntius\Nuntius::addCommands($application);

    try {
      $application->find('system:capsule_disable');
      $application->find('system:capsusle_enable');
    } catch (\Exception $e) {
      $this->fail('The system service was not found.');
    }
  }

}
