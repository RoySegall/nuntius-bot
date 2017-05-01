<?php

namespace tests\overrides;

use Nuntius\Dispatcher\GitHubEvent;
use Nuntius\Examples\GitHubOpened\NuntiusGitHubOpenedExample;
use Nuntius\Nuntius;

/**
 * Overriding the original method and just logging the data.
 */
class NuntiusGitHubOpenedExampleOverride extends NuntiusGitHubOpenedExample {

  /**
   * Overriding the original method and just logging the data.
   *
   * @param GitHubEvent $event
   *   The event object with information about the PR/issue.
   */
  public function act(GitHubEvent $event) {
    $payload = $this->getPayload($event);

    Nuntius::getEntityManager()->get('logger')->insert([
      'logging' => 'opened_' . $payload['key'],
      'payload' => $payload,
    ]);
  }

}