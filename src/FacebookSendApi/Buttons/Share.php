<?php

namespace Nuntius\FacebookSendApi\Buttons;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class Share.
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/share-button
 */
class Share extends SendAPITransform {

  /**
   * Share constructor.
   */
  public function __construct() {
    $this->data['type'] = 'element_share';
  }

}
