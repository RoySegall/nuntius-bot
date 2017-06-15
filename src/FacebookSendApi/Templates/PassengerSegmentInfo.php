<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class PassengerSegmentInfo
 */
class PassengerSegmentInfo extends SendAPITransform {

  /**
   * Set the segment ID.
   *
   * @param $segment_id
   *   The segment ID.
   *
   * @return $this
   */
  public function segmentId($segment_id) {
    $this->data['segment_id'] = $segment_id;

    return $this;
  }

  /**
   * Set the passenger ID.
   *
   * @param $passenger_id
   *   The passenger ID.
   * @return $this
   */
  public function passengerId($passenger_id) {
    $this->data['passenger_id'] = $passenger_id;

    return $this;
  }

  /**
   * Set the seat.
   *
   * @param $seat
   *   The seat.
   *
   * @return $this
   */
  public function seat($seat) {
    $this->data['seat'] = $seat;

    return $this;
  }

  /**
   * Set the seat type.
   *
   * @param $seat_type
   *   The seat type.
   *
   * @return $this
   */
  public function seatType($seat_type) {
    $this->data['seat_type'] = $seat_type;

    return $this;
  }

  /**
   * Add a product info.
   *
   * @param $title
   *   The product title.
   * @param $value
   *   The product value.
   *
   * @return $this
   */
  public function addProductInfo($title, $value) {
    $this->data['product_info'][] = [
      'title' => $title,
      'value' => $value,
    ];

    return $this;
  }

  /**
   * Reset the product info.
   * Useful when using the same object for multiple segments.
   *
   * @return $this
   */
  public function resetProductInfo() {
    $this->data['product_info'] = [];

    return $this;
  }

}
