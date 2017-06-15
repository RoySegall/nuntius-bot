<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\Buttons\Url;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class AirlineItinerary
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/airline-itinerary-template
 */
class AirlineItinerary extends SendAPITransform {

  /**
   * AirlineItinerary constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'airline_itinerary';
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
   * Add passenger info.
   *
   * @param PassengerInfo $passenger_info
   *   Passenger info object.
   *
   * @return $this
   */
  public function passengerInfo(PassengerInfo $passenger_info) {
    $this->data['attachment']['payload']['passenger_info'][] = $passenger_info->getData();

    return $this;
  }

  /**
   * Add a flight info object to the payload.
   *
   * @param FlightInfo $flight_info
   *   The flight info object.
   *
   * @return $this
   */
  public function flightInfo(FlightInfo $flight_info) {
    $this->data['attachment']['payload']['flight_info'][] = $flight_info->getData();

    return $this;
  }

  /**
   * Add a passenger info object to the payload.
   *
   * @param PassengerSegmentInfo $passenger_segment_info
   *   The passenger segment info object,
   *
   * @return $this
   */
  public function passengerSegmentInfo(PassengerSegmentInfo $passenger_segment_info) {
    $this->data['attachment']['payload']['passenger_segment_info'][] = $passenger_segment_info->getData();

    return $this;
  }

  /**
   * Add price info object.
   *
   * @param PriceInfo $price_info
   *   The price info object.
   *
   * @return $this
   */
  public function priceInfo(PriceInfo $price_info) {
    $this->data['attachment']['payload']['price_info'][] = $price_info->getData();

    return $this;
  }

  /**
   * Set the base price.
   *
   * @param $base_price
   *   The base price.
   *
   * @return $this
   */
  public function basePrice($base_price) {
    $this->data['attachment']['payload']['base_price'] = $base_price;

    return $this;
  }

  /**
   * Set the tax.
   *
   * @param $tax
   *   The tax.
   *
   * @return $this
   */
  public function tax($tax) {
    $this->data['attachment']['payload']['tax'] = $tax;

    return $this;
  }

  /**
   * Set the total price.
   *
   * @param $total_price
   *   The total price.
   *
   * @return $this
   */
  public function totalPrice($total_price) {
    $this->data['attachment']['payload']['total_price'] = $total_price;

    return $this;
  }

  /**
   * Set the currency.
   *
   * @param $currency
   *   The total price.
   *
   * @return $this
   */
  public function currency($currency) {
    $this->data['attachment']['payload']['currency'] = $currency;

    return $this;
  }

}
