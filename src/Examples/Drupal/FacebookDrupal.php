<?php

namespace Nuntius\WebhooksRounting;

use Nuntius\Examples\Drupal\DrupalBaseWebhook;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handling incoming webhooks from Drupal.
 *
 * This is a ready to go webhoook routing for nuntius. This webhook will post
 * message to a a room about a new node.
 */
class FacebookDrupal extends DrupalBaseWebhook {

  /**
   * {@inheritdoc}
   */
  protected function trigger() {
    return new Response();
  }

}
