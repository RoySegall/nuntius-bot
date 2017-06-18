<?php

namespace tests\overrides;

use Nuntius\WebhooksRounting\Facebook;

/**
 * Overriding Facebook default webhook listener for testing purpose.
 */
class NuntiusFacebookOverride extends Facebook {

  /**
   * NuntiusFacebookOverride constructor.
   */
  public function __construct() {
    parent::__construct();

    $this->sendAPI = new NuntiusSendAPI();
  }

}