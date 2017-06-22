<?php

namespace tests\overrides;

use FacebookMessengerSendApi\SendAPI;
use FacebookMessengerSendApi\SendAPITransformInterface;
use Nuntius\Nuntius;

/**
 * Overriding the original method and just logging the data.
 */
class NuntiusSendAPI extends SendAPI {

  /**
   * {@inheritdoc}
   */
  public function sendMessage($text) {

    if ($text instanceof SendAPITransformInterface) {
      $message = $text->getData();
    }
    else {
      $message = !is_array($text) ? $message = ['text' => $text] : $text;
    }

    Nuntius::getDb()->getStorage()->table('logger')->save($message);
  }

}
