<?php

namespace Nuntius\Examples\Names;

class Tom implements NameInterface {

  /**
   * {@inheritdoc}
   */
  public function name(NameEvent $event) {
    $event->addName('Major Tom');
  }

}
