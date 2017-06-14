<?php

namespace Nuntius\FacebookSendApi\ContentType;

use Nuntius\FacebookSendApi\SendAPITransform;

class Video extends SendAPITransform {

  /**
   * Video constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'video';
  }

  /**
   * Set the URl of the file.
   *
   * @param $url
   *   The URL of the file.
   *
   * @return $this
   *   Return the current object.
   */
  public function url($url) {
    $this->data['attachment']['payload']['url'] = $url;

    return $this;
  }

}