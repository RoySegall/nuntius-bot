<?php

namespace Nuntius\Examples\Names;

interface NameInterface {

  /**
   * Dispatched event method.
   *
   * @param NameEvent $event
   *   The event name object.
   */
  public function name(NameEvent $event);

}
