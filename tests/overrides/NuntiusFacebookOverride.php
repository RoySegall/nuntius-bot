<?php

namespace tests\overrides;

use Nuntius\Dispatcher\GitHubEvent;
use Nuntius\Examples\GitHubOpened\NuntiusGitHubOpenedExample;
use Nuntius\Nuntius;
use Nuntius\WebhooksRounting\Facebook;

/**
 * Overriding Facebook default webhook listener for testing purpose.
 */
class NuntiusFacebookOverride extends Facebook {

  /**
   * NuntiusFacebookOverride constructor.
   */
  public function __construct() {
    $this->sendAPI = new NuntiusSendAPI();
  }

}