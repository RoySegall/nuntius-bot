<?php

namespace Nuntius\FacebookSendApi\ContentType;

use Nuntius\FacebookSendApi\SendAPITransform;

class File extends SendAPITransform {

  /**
   * File constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'file';
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