<?php

namespace Nuntius\System;

use Nuntius\Nuntius;

class System {

  /**
   * @return HooksDispatcher
   * @throws \Exception
   */
  public static function hooksDispatcher() {
    return Nuntius::container()->get('hooks_dispatcher');
  }
}