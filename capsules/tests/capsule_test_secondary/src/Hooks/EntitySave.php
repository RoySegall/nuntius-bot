<?php

namespace Nuntius\CapsuleTestSecondary\Hooks;

use Nuntius\System\HookBaseClass;

class EntitySave extends HookBaseClass {

  /**
   * {@inheritdoc}
   */
  public function alter(&$arguments) {
    $arguments['entity']->name = 'bar';
    $arguments['entity']->foo = 'bar';
  }

}
