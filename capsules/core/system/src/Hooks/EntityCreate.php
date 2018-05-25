<?php

namespace Nuntius\System\Hooks;

use Nuntius\System\HookBaseClass;

class EntityCreate extends HookBaseClass {

  /**
   * {@inheritdoc}
   */
  public function invoke() {
    \Kint::dump('a');
  }
}
