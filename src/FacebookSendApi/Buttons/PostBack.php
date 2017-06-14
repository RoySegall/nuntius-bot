<?php

namespace Nuntius\FacebookSendApi\Buttons;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class PostBack.
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/postback-button
 */
class PostBack extends SendAPITransform implements ButtonInterface {

  /**
   * PostBack constructor.
   */
  public function __construct() {
    $this->data['type'] = 'postback';
  }

  /**
   * Set the title.
   *
   * @param $title
   *   The title.
   *
   * @return $this
   */
  public function title($title) {
    $this->data['title'] = $title;

    return $this;
  }

  /**
   * Set the payload.
   *
   * @param $payload
   *   The payload.
   *
   * @return $this
   */
  public function payload($payload) {
    $this->data['payload'] = $payload;

    return $this;
  }

}
