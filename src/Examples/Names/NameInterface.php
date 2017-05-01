<?php

namespace Nuntius\Examples\Names;

/**
 * Interface of dispatched events class.
 */
interface NameInterface {

  /**
   * Dispatched event method.
   *
   * @param NameEvent $event
   *   The event name object.
   */
  public function name(NameEvent $event);

}
