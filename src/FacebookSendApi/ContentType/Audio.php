<?php

namespace Nuntius\FacebookSendApi\ContentType;

use Nuntius\FacebookSendApi\SendAPITransform;

class Audio extends SendAPITransform {

  /**
   * Audio constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'audio';
  }

  /**
   * Set the URl of the audio.
   *
   * @param $url
   *   The URL of the audio.
   *
   * @return $this
   *   Return the current object.
   */
  public function url($url) {
    $this->data['attachment']['payload']['url'] = $url;

    return $this;
  }

}
