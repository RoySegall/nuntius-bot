<?php

namespace Nuntius\Examples\Names;

/**
 * Add name to the name event object. Used for demo purpose.
 */
class Tom implements NameInterface {

  /**
   * {@inheritdoc}
   */
  public function name(NameEvent $event) {
    $event->addName('Major Tom');
  }

}
