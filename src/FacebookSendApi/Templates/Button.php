<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class Button
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/button-template
 */
class Button extends SendAPITransform {

  /**
   * Buy constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'button';
  }

  /**
   * Set the text.
   *
   * @param $text
   *   The text of the button.
   *
   * @return $this
   */
  public function text($text) {
    $this->data['attachment']['payload']['text'] = $text;

    return $this;
  }

  /**
   * Adding a button. Support up to 3 buttons.
   *
   * @param ButtonInterface $button
   *   The button interface object.
   *
   * @return $this
   *
   * @throws \Exception
   */
  public function addButton(ButtonInterface $button) {
    if (!empty($this->data['attachment']['payload']['buttons'])) {

      if (count($this->data['attachment']['payload']['buttons']) >= 3) {
        throw new \Exception('You cannot send more than 3 buttons.');
      }
    }

    $this->data['attachment']['payload']['buttons'][] = $button->getData();

    return $this;
  }

}
