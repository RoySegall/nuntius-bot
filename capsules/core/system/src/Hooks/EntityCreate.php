<?php

namespace Nuntius\System\Hooks;

use Nuntius\System\HookBaseClass;

class EntityCreate extends HookBaseClass {

  /**
   * {@inheritdoc}
   */
  public function alter($arguments) {
    $arguments['entity']->setNum(11);
  }
}
