<?php

namespace Nuntius\FacebookSendApi\Buttons;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class LogOut.
 *
 * @see https://developers.facebook.com/docs/messenger-platform/account-linking/unlink-account
 */
class LogOut extends SendAPITransform implements ButtonInterface {

  /**
   * LogOut constructor.
   */
  public function __construct() {
    $this->data['type'] = 'account_unlink';
  }

}
