<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\Buttons\Url;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class AirlineCheckIn
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/airline-checkin-template
 */
class AirlineCheckIn extends SendAPITransform {

  /**
   * AirlineCheckIn constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'airline_checkin';
  }

  /**
   * Set the intro message.
   *
   * @param $intro_message
   *   The intro message.
   *
   * @return $this
   */
  public function introMessage($intro_message) {
    $this->data['attachment']['payload']['intro_message'] = $intro_message;

    return $this;
  }

  /**
   * Set the locale.
   *
   * @param $locale
   *   The user's locale.
   *
   * @return $this
   */
  public function locale($locale) {
    $this->data['attachment']['payload']['locale'] = $locale;

    return $this;
  }

  /**
   * Set the theme color.
   *
   * @param $theme_color
   *   The locale color.
   *
   * @return $this
   */
  public function themeColor($theme_color) {
    $this->data['attachment']['payload']['theme_color'] = $theme_color;

    return $this;
  }

  /**
   * Set the passenger number.
   *
   * @param $pnr_number
   *   The passenger number.
   *
   * @return $this
   */
  public function pnrNumber($pnr_number) {
    $this->data['attachment']['payload']['pnr_number'] = $pnr_number;

    return $this;
  }

  /**
   * The flight number.
   *
   * @param $flight_number
   *   The flight number.
   *
   * @return $this
   */
  public function flightNumber($flight_number) {
    $this->data['attachment']['payload']['flight_info'][0]['flight_number'] = $flight_number;

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
    $this->data['attachment']['payload']['flight_info'][0]['departure_airport'] = $departure_airport->getData();

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
    $this->data['attachment']['payload']['flight_info'][0]['arrival_airport'] = $arrival_airport->getData();

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
    $this->data['attachment']['payload']['flight_info'][0]['flight_schedule']['boarding_time'] = $boarding_time;

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
    $this->data['attachment']['payload']['flight_info'][0]['flight_schedule']['departure_time'] = $departure_time;

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
    $this->data['attachment']['payload']['flight_info'][0]['flight_schedule']['arrival_time'] = $arrival_time;

    return $this;
  }

  /**
   * Set the checking URL.
   *
   * @param $checkin_url
   *   The checking URL.
   *
   * @return $this
   */
  public function checkinUrl($checkin_url) {
    $this->data['attachment']['payload']['checkin_url'] = $checkin_url;

    return $this;
  }


}
