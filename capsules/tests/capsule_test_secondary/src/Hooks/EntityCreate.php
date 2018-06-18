<?php

namespace Nuntius\CapsuleTestSecondary\Hooks;

use Nuntius\System\HookBaseClass;

class EntityCreate extends HookBaseClass {

  /**
   * {@inheritdoc}
   */
  public function alter(&$arguments) {
    $arguments['entity']->description = 'new description';
  }

}
