<?php

namespace Nuntius\System;

use Nuntius\Capsule\CapsuleErrorException;
use Nuntius\Capsule\CapsuleServiceInterface;
use Nuntius\Nuntius;

/**
 * {@inheritdoc}
 */
class HooksDispatcher implements HooksDispatcherInterface {

  public $arguments = [];

  /**
   * @var CapsuleServiceInterface
   */
  protected $capsuleService;

  /**
   * {@inheritdoc}
   */
  public function __construct(CapsuleServiceInterface $capsule_service) {
    $this->capsuleService = $capsule_service;
  }

  /**
   * {@inheritdoc}
   */
  public function invoke($hook_name) {
    $invokes = $this->initClass($hook_name);

    foreach ($invokes as $invoke) {
      $invoke->invoke($this->arguments);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function alter($hook_name) {
    $invokes = $this->initClass($hook_name);

    foreach ($invokes as $invoke) {
      $invoke->alter($this->arguments);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setArguments(&$arguments = []) {
    $this->arguments = &$arguments;
    return $this;
  }

  /**
   * @param $hook_name
   * @return HookInterface[]
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   * @throws \Exception
   */
  protected function initClass($hook_name) {
    $capsules = $this->capsuleService->getCapsulesForBootstrapping();
    $hooks_instance = [];
    foreach ($capsules as $capsule) {
      try {
        $hooks = $this
          ->capsuleService
          ->getCapsuleImplementations($capsule['machine_name'], 'hooks');
      } catch (CapsuleErrorException $e) {
        continue;
      }

      if (!$hooks) {
        continue;
      }

      if (!in_array($hook_name, array_keys($hooks))) {
        continue;
      }

      $class = $hooks[$hook_name]['class'];

      // Check if the hook need the container or not.
      if (method_exists($class, 'getContainer')) {
        $hooks_instance[] = call_user_func(array($class, 'getContainer'), Nuntius::container());
      }
      else {
        $hooks_instance[] = new $class;
      }
    }

    return $hooks_instance;
  }
}
