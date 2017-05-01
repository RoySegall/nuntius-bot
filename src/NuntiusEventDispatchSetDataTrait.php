<?php

namespace Nuntius;

/**
 * Trait for event dispatching objects.
 *
 * When dispatching an event with information we need setters and getters method
 * of the data. This trait will provide it easilly.
 */
trait NuntiusEventDispatchSetDataTrait {

  /**
   * Event data.
   *
   * @var array
   */
  protected $data = [];

  /**
   * Set data.
   *
   * @param array $data
   *   Event data.
   *
   * @return $this
   *   The current instance.
   */
  public function setData($data) {
    $this->data = $data;

    return $this;
  }

  /**
   * Get data for the event.
   *
   * @return array
   *   The event data.
   */
  public function getData() {
    return $this->data;
  }

}
