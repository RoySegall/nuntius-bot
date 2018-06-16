<?php

namespace Nuntius\CapsuleTestSecondary\Hooks;

use Nuntius\System\HookBaseClass;

class EntityDelete extends HookBaseClass {

  /**
   * {@inheritdoc}
   */
  public function alter(&$arguments) {
    throw new \Exception('Deleted ' . $arguments['entity']->id);
  }

}
