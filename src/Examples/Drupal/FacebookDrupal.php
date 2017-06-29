<?php

namespace Nuntius\Examples\Drupal;

use Nuntius\Nuntius;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handling incoming webhooks from Drupal.
 */
class FacebookDrupal extends DrupalBaseWebhook {

  /**
   * {@inheritdoc}
   */
  protected function trigger() {
    // Look for registered users from the given URL.
    if (!$users = Nuntius::getDb()->getQuery()->table('fb_reminders')->execute()) {
      return new Response();
    }

    // Prepare the send API object.
    $send_api = Nuntius::facebookSendApi();
    $send_api->setAccessToken(Nuntius::getSettings()->getSetting('fb_token'));

    $element = $send_api->templates->element;
    $payload = $send_api->templates->generic
      ->addElement(
        $element
          ->title($this->payload['title'])
          ->subtitle($this->payload['body'])
          ->addButton($send_api->buttons->url->title('Take me there!')->url($this->url))
      );

    // Loop over the users.
    foreach ($users as $user) {
      $send_api
        ->setRecipientId($user['recipient_id'])
        ->sendMessage($payload);
    }

    return new Response();
  }

}
