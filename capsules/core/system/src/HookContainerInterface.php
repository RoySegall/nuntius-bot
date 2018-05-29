<?php

namespace Nuntius\System;

/**
 * The reason why we need this interface is for dependency injection in hooks.
 *
 * Yes, we could define the hooks as a service and then call it when the hook
 * is needed. A service might be needed at any time but a hook might get called
 * only when an event happens. That event might happens only once and the amount
 * of hooks might be bigger than the number of services. If we will load 25, and
 * more services, hooks to the service container we will get bad performance.
 *
 * This interface make sure that when the hook is invoked it will instnciate
 * the services. For example:
 * @code
 *
 * class EntityCreate extends HookBaseClass implements HookContainerInterface {
 *  static function getContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container) {
 *    return new self($container->get('capsule_manager'));
 *  }
 *
 *  public function __construct(CapsuleServiceInterface $capsule_service) {
 *    $this->capsuleService = $capsule_service;
 *  }
 * }
 * @endcode
 */
interface HookContainerInterface {

  /**
   * Using the container to use dependency injection to hooks.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   * @return mixed
   */
  static function getContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container);

}
