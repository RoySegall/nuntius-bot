<?php

namespace Nuntius\System;

use Nuntius\Nuntius;

class System {

  /**
   * Get the hooks dispatcher service.
   *
   * @return HooksDispatcher
   *  The hook dispatcher service.
   *
   * @throws \Exception
   */
  public static function hooksDispatcher() {
    return Nuntius::container()->get('hooks_dispatcher');
  }
}
