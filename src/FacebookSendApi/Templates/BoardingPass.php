<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class BoardingPass
 */
class BoardingPass extends SendAPITransform {

  /**
   * Set the passenger name.
   *
   * @param $passenger_name
   *   The passenger name.
   *
   * @return $this
   */
  public function passengerName($passenger_name) {
    $this->data['passenger_name'] = $passenger_name;

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
    $this->data['pnr_number'] = $pnr_number;

    return $this;
  }

  /**
   * Set the travel class.
   *
   * @param $travel_class
   *   Set the travel class.
   *
   * @return $this
   */
  public function travelClass($travel_class) {
    $this->data['travel_class'] = $travel_class;

    return $this;
  }

  /**
   * Set the seat.
   *
   * @param $seat
   *   The seat of the user.
   *
   * @return $this
   */
  public function seat($seat) {
    $this->data['seat'] = $seat;

    return $this;
  }

  /**
   * Add auxiliary fields.
   *
   * @param $label
   *   The label of the field.
   * @param $value
   *   The value of the field.
   *
   * @return $this.
   */
  public function addAuxiliaryFields($label, $value) {
    $this->data['auxiliary_fields'][] = [
      'label' => $label,
      'value' => $value,
    ];

    return $this;
  }

  /**
   * Add secondary fields.
   *
   * @param $label
   *   The label of the field.
   * @param $value
   *   The value of the field.
   *
   * @return $this.
   */
  public function addSecondaryFields($label, $value) {
    $this->data['secondary_fields'][] = [
      'label' => $label,
      'value' => $value,
    ];

    return $this;
  }

  /**
   * Set the logo URL.
   *
   * @param $logo_image_url
   *   The address.
   *
   * @return $this
   */
  public function logoImageUrl($logo_image_url) {
    $this->data['logo_image_url'] = $logo_image_url;
    
    return $this;
  }

  /**
   * Set the header image URL.
   *
   * @param $header_image_url
   *   The image URL.
   *
   * @return $this
   */
  public function headerImageUrl($header_image_url) {
    $this->data['header_image_url'] = $header_image_url;

    return $this;
  }

  /**
   * Set the header text.
   *
   * @param $header_text_field
   *   The header text.
   *
   * @return $this
   */
  public function headerTextField($header_text_field) {
    $this->data['header_text_field'] = $header_text_field;

    return $this;
  }

  /**
   * Set the QR code.
   *
   * @param $qr_code
   *   The URl of the QR code.
   *
   * @return $this
   */
  public function qrCode($qr_code) {
    $this->data['qr_code'] = $qr_code;

    return $this;
  }

  /**
   * Set the barcode image url. If you don't have a QR code set use this method.
   *
   * @param $barcode_image_url
   *   The address of the QR code.
   *
   * @return $this
   */
  public function barcodeImageUrl($barcode_image_url) {
    $this->data['barcode_image_url'] = $barcode_image_url;

    return $this;
  }

  /**
   * The image url above the bar code.
   *
   * @param $above_bar_code_image_url
   *   The image URL.
   *
   * @return $this
   */
  public function aboveBarCodeImageUrl($above_bar_code_image_url) {
    $this->data['above_bar_code_image_url'] = $above_bar_code_image_url;

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
    $this->data['flight_info']['flight_number'] = $flight_number;

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
    $this->data['flight_info']['departure_airport'] = $departure_airport->getData();

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
    $this->data['flight_info']['arrival_airport'] = $arrival_airport->getData();

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
    $this->data['flight_info']['flight_schedule']['boarding_time'] = $boarding_time;

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
    $this->data['flight_info']['flight_schedule']['departure_time'] = $departure_time;

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
    $this->data['flight_info']['flight_schedule']['arrival_time'] = $arrival_time;

    return $this;
  }

}
