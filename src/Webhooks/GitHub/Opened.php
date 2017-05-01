<?php

namespace Nuntius\Webhooks\GitHub;

use Nuntius\GitHubWebhooksAbstract;
use Nuntius\GitHubWebhooksInterface;
use Nuntius\Nuntius;

/**
 * Acting upon issue or pull request opening.
 */
class Opened extends GitHubWebhooksAbstract implements GitHubWebhooksInterface {

  /**
   * {@inheritdoc}
   */
  public function act() {
    Nuntius::getDispatcher()->dispatch('github_opened', $this->data);
  }


  public function postAct() {

  }
}
