<?php

namespace Nuntius\FacebookSendApi\ContentType;

use Nuntius\FacebookSendApi\SendAPITransform;

class Text extends SendAPITransform {

  /**
   * Set the text.
   *
   * @param string $text
   *   The text.
   *
   * @return $this
   */
  public function text($text) {
    $this->data['text'] = $text;

    return $this;
  }

}
