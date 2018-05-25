<?php

namespace Nuntius\System;

use Nuntius\Capsule\CapsuleServiceInterface;

/**
 * Interface HooksDispatcherInterface
 * @package Nuntius\System
 *
 * What are hooks? Hooks defined as a mechanism which allow to parts of a
 * software to interact with a system or a bigger part of software. For example:
 * A third party service provides to a software an integration with a third
 * party search software(Apache solr, elasticsearch). That search service want
 * to index any entity in the system and want to replace search in the normal DB
 * with searching in the search service it self.
 *
 * The only way to provide this actions is to notify the third party software
 * that events happens and allow him to integrate with the events through hooks.
 *
 * In previous versions, the way to do this was to use Symfony event dispatcher
 * but that was came along with a poor DX. The new hooks mechanism define each
 * event registering through the services yml file:
 *
 * @code
 * hooks:
 *  HOOK_NAME:
 *  class: CLASS
 * @endcode
 *
 * HOOK_NAME - the event: entity_create, query_alter etc. etc.
 * CLASS - the class to hold the methods.
 *
 * The class need to implements HookInterface or extends HookBaseClass. Each one
 * of the class need to have invoke method and alter method. More about them you
 * can read in their documentation.
 */
interface HooksDispatcherInterface {

  /**
   * HooksDispatcherInterface constructor.
   *
   * @param CapsuleServiceInterface $capsule_service
   *  The capsule service.
   */
  public function __construct(CapsuleServiceInterface $capsule_service);

  /**
   * Invoking a hook.
   *
   * Invoke method happen when an event happen. If the event need to alter some
   * data in the event use the alter method.
   *
   * @param string $hook_name
   *  The hook name.
   */
  public function invoke($hook_name);

  /**
   * The alter method used for changing a data in the event.
   *
   * @param string $hook_name
   *  The hook name.
   */
  public function alter($hook_name);

}
