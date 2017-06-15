<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\Buttons\Url;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class AirlineBoarding
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/airline-boardingpass-template
 */
class AirlineBoarding extends SendAPITransform {

  /**
   * AirlineBoarding constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'airline_boardingpass';
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
   * Add boarding pass.
   *
   * @param BoardingPass $boardingPass
   *   Add a boarding pass.
   *
   * @return $this
   */
  public function addBoardingPass(BoardingPass $boardingPass) {
    $this->data['attachment']['payload']['boarding_pass'] = $boardingPass->getData();

    return $this;
  }
}
