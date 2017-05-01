<?php

namespace Nuntius;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Dispatching evenets.
 */
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
   * Dispatching the event.
   *
   * @param $event
   *   The event type.
   * @param array $data
   *   Set data to the event dispatcher argument thus ensuring context aware.
   *
   * @return \Symfony\Component\EventDispatcher\Event
   *   The events object after the the events was dispatched.
   */
  public function dispatch($event, $data = NULL) {
    $event_dispatcher = $this->getEventDispatcher($event);

    if (method_exists($event_dispatcher, 'setData') && $data) {
      $event_dispatcher->setData($data);
    }

    return $this->dispatcher->dispatch($event, $event_dispatcher);
  }

  /**
   * Get the event dispatcher object.
   *
   * @param $event
   *   The event name.
   *
   * @return EventDispatcher
   *   The event object.
   */
  protected function getEventDispatcher($event) {
    $class = Nuntius::getSettings()->getSetting('dispatcher')[$event];

    return new $class;
  }

}
