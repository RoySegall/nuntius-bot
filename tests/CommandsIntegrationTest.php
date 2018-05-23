<?php

namespace tests;

use Nuntius\Capsule\CapsuleErrorException;
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

  /** @var DbDispatcher */
  protected $db;

  /**
   * Testing the commands integration with other modules.
   */
  public function testCommandsIntegrations() {
    $application = new \Symfony\Component\Console\Application();

    // First we need to enable the system capsule.
    $this->capsuleService->enableCapsule('system');

    \Nuntius\Nuntius::addCommands($application);

    \Kint::dump(($application->find('system:capsule_disable')));
  }

}
