<?php

namespace Nuntius\CapsuleTestSecondary\Hooks;

use Nuntius\System\HookBaseClass;

class EntityLoad extends HookBaseClass {

  /**
   * {@inheritdoc}
   */
  public function alter(&$arguments) {
//    $arguments['entity']->name = 'bar';

    foreach ($arguments['entities'] as &$entity) {
      $entity->name = 'foo';
    }
  }

}
