<?php

namespace SlackHttpService\Payloads;

/**
 * Base class to all payload objects.
 *
 * Payloads array is constructed sub classes of this sub class for one reason:
 * self discovery. The self discovery will give to programmers know what kind of
 * information they can pass in the payload.
 */
abstract class SlackHttpPayloadServiceAbstract {

  /**
   * The payload object.
   *
   * @var array
   */
  protected $payload = [];

  /**
   * Constructing the class.
   */
  function __construct() {
    $this->setDefaults();
  }

  /**
   * Get the payload.
   *
   * @return array
   *   Get the payload.
   */
  public function getPayload() {
    return $this->payload;
  }

  /**
   * Set defaults of payload values.
   *
   * @return mixed
   */
  abstract protected function setDefaults();

}
