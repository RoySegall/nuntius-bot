<?php

namespace Nuntius\Examples\Names;

class HAL9000 implements NameInterface {

  /**
   * {@inheritdoc}
   */
  public function name(NameEvent $event) {
    $event->addName('HAL9000');
  }

}
