<?php

namespace tests;

use Nuntius\Examples\Names\NameEvent;
use Nuntius\Nuntius;

/**
 * Testing event dispatcher.
 */
class EventDispatcherTest extends TestsAbstract {

  /**
   * Testing update manager.
   */
  public function testEntitiesCrud() {
    $event_dispatcher = Nuntius::getDispatcher();
    
    /** @var NameEvent $names */
    $names = $event_dispatcher->dispatch('names');

    $this->assertEquals($names->getNames(), ['Major Tom', 'HAL9000']);
  }

}
