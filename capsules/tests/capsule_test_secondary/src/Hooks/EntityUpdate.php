<?php

namespace Nuntius\CapsuleTestSecondary\Hooks;

use Nuntius\System\HookBaseClass;

class EntityUpdate extends HookBaseClass {

  /**
   * {@inheritdoc}
   */
  public function alter(&$arguments) {
    $arguments['entity']->name = 'checking updated';
  }

}
