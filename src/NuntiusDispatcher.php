<?php

namespace Nuntius;

use Symfony\Component\EventDispatcher\EventDispatcher;

class NuntiusDispatcher {

  /**
   * List of all dispatchers.
   *
   * @var array.
   */
  protected $dispatchers;

  /**
   * The dispatcher object.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;

  /**
   * NuntiusDispatcher constructor.
   *
   * @param NuntiusConfig $config
   *   The config service.
   */
  function __construct(NuntiusConfig $config) {
    $this->dispatchers = $config->getSetting('dispatchers');
  }

  /**
   * Building the dispatcher object.
   *
   * @return $this
   *   The current instance.
   */
  public function buildDispatcher() {
    if ($this->dispatcher) {
      return $this;
    }

    $this->dispatcher = new EventDispatcher();

    foreach ($this->dispatchers as $event => $handlers) {
      foreach ($handlers as $handler) {
        list($class, $method) = explode('::', $handler);
        $this->dispatcher->addListener($event, [new $class, $method]);
      }
    }

    return $this;
  }

  /**
   * @param $event
   *   The event type.
   *
   * @return \Symfony\Component\EventDispatcher\Event
   *   The events object after the the events was dispatched.
   */
  public function dispatch($event) {
    $class = Nuntius::getSettings()->getSetting('dispatcher')[$event];
    return $this->dispatcher->dispatch($event, new $class);
  }

}
