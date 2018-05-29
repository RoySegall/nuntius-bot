<?php

namespace Nuntius\System;

/**
 * Interface HookInterface
 *
 * Each implementation of a hook need to implement this interface.
 *
 * @package Nuntius\System
 */
interface HookInterface {

  /**
   * Integrating with an invocation of a hook.
   *
   * @param $arguments
   *  Arguments passed to the hooks by who ever invoke the action.
   */
  public function invoke($arguments);

  /**
   * Altering values which set by who ever invoke the action.
   *
   * @param $arguments
   *  The arguments to change.
   */
  public function alter(&$arguments);

};
