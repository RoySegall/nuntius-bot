<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class PassengerInfo
 */
class PassengerInfo extends SendAPITransform {

  /**
   * Set the passenger ID.
   *
   * @param $passenger_id
   *   The passenger ID.
   *
   * @return $this
   */
  public function passengerId($passenger_id) {
    $this->data['passenger_id'] = $passenger_id;

    return $this;
  }

  /**
   * Set the ticket number.
   *
   * @param $ticket_number
   *   The ticket number.
   *
   * @return $this
   */
  public function ticketNumber($ticket_number) {
    $this->data['ticket_number'] = $ticket_number;

    return $this;
  }

  /**
   * Set the name.
   *
   * @param $name
   *   The name.
   *
   * @return $this
   */
  public function name($name) {
    $this->data['name'] = $name;

    return $this;
  }

}
