<?php

namespace Nuntius\WebhooksRounting;

use Nuntius\Examples\Drupal\DrupalBaseWebhook;
use Nuntius\Nuntius;
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
    $subtitle = !empty($this->payload->body->und[0]->value) ? $this->payload->body->und[0]->value : '';

    // Look for registered users from the given URL.
    $users = [];

    // Prepare the send API object.
    $send_api = \Nuntius\Nuntius::facebookSendApi();
    $send_api->setAccessToken(Nuntius::getSettings()->getSetting('fb_token'));

    $element = $send_api->templates->element;
    $payload = $send_api->templates->generic
      ->addElement(
        $element
          ->title($this->payload->title)
          ->subtitle($subtitle)
          ->addButton($send_api->buttons->url->title('Take me there!')->url($this->url))
      );

    // Loop over the users.
    foreach ($users as $user) {
      $send_api
        ->setRecipientId($user->recpient_id)
        ->sendMessage($payload);
    }

    return new Response();
  }

}
