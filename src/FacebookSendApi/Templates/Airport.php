<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class Airport
 */
class Airport extends SendAPITransform {

  /**
   * Set the airport code.
   *
   * @param $airport_code
   *   The airport code.
   *
   * @return $this
   */
  public function airportCode($airport_code) {
    $this->data['airport_code'] = $airport_code;

    return $this;
  }

  /**
   * Set the city.
   *
   * @param $city
   *   The city.
   *
   * @return $this
   */
  public function city($city) {
    $this->data['city'] = $city;

    return $this;
  }

  /**
   * Set the terminal.
   *
   * @param $terminal
   *   The terminal.
   *
   * @return $this
   */
  public function terminal($terminal) {
    $this->data['terminal'] = $terminal;

    return $this;
  }

  /**
   * Set the gate.
   *
   * @param $gate
   *   The gate.
   *
   * @return $this
   */
  public function gate($gate) {
    $this->data['gate'] = $gate;

    return $this;
  }

}
