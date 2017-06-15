<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\Buttons\Url;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class AirlineFlightUpdate
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/airline-update-template
 */
class AirlineFlightUpdate extends SendAPITransform {

  /**
   * AirlineFlightUpdate constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'airline_update';
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
   * Set the update type.
   *
   * @param $update_type
   *   The update type.
   *
   * @return $this
   */
  public function updateType($update_type) {
    $this->data['attachment']['payload']['update_type'] = $update_type;

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
   * Set the update flight info.
   *
   * @param UpdateFlightInfo $update_flight_info
   *   Update flight info object.
   * @return $this
   */
  public function updateFlightInfo(UpdateFlightInfo $update_flight_info) {
    $this->data['attachment']['payload']['update_flight_info'] = $update_flight_info->getData();

    return $this;
  }

}
