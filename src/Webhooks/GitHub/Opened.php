<?php

namespace Nuntius\Webhooks\GitHub;

use Nuntius\GitHubWebhooksAbstract;
use Nuntius\GitHubWebhooksInterface;

/**
 * Acting upon issue or pull request opening.
 */
class Opened extends GitHubWebhooksAbstract implements GitHubWebhooksInterface {

  /**
   * {@inheritdoc}
   */
  public function act() {
    $payload = $this->data;
    $key = !empty($payload->pull_request) ? 'pull_request' : 'issue';

    $payload = [
      'event' => 'open',
      'type' => $key,
      'user' => $payload->{$key}->user->login,
      'title' => $payload->{$key}->title,
      'body' => $payload->{$key}->body,
    ];

    $this->logger->insert([
      'logging' => 'opened_' . $key,
      'payload' => $payload,
    ]);
  }

}
