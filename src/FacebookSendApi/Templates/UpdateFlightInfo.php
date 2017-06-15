<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\Buttons\Url;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class UpdateFlightInfo
 */
class UpdateFlightInfo extends SendAPITransform {

  /**
   * The flight number.
   *
   * @param $flight_number
   * @return $this
   */
  public function flightNumber($flight_number) {
    $this->data['flight_number'] = $flight_number;

    return $this;
  }

  /**
   * Set the departure airport.
   *
   * @param Airport $departure_airport
   *   An airport object.
   *
   * @return $this
   */
  public function departureAirport(Airport $departure_airport) {
    $this->data['departure_airport'] = $departure_airport->getData();

    return $this;
  }

  /**
   * Set hte arrival airport.
   *
   * @param Airport $arrival_airport
   *   The air port object.
   *
   * @return $this
   */
  public function arrivalAirport(Airport $arrival_airport) {
    $this->data['arrival_airport'] = $arrival_airport->getData();

    return $this;
  }

  /**
   * The flight boarding time.
   *
   * @param $boarding_time
   *   The boarding time.
   *
   * @return $this
   */
  public function boardingTime($boarding_time) {
    $this->data['flight_schedule']['boarding_time'] = $boarding_time;

    return $this;
  }

  /**
   * Set the departure time.
   *
   * @param $departure_time
   *   The departure time.
   *
   * @return $this
   */
  public function departureTime($departure_time) {
    $this->data['flight_schedule']['departure_time'] = $departure_time;

    return $this;
  }

  /**
   * Set the arrival time.
   *
   * @param $arrival_time
   *   The arrival time.
   *
   * @return $this
   */
  public function arrivalTime($arrival_time) {
    $this->data['flight_schedule']['arrival_time'] = $arrival_time;

    return $this;
  }


}
