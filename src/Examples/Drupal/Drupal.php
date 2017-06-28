<?php

namespace Nuntius\Examples\Drupal;

use Nuntius\Nuntius;
use Nuntius\WebhooksRoutingControllerInterface;
use SlackHttpService\Payloads\SlackHttpPayloadServiceAttachments;
use SlackHttpService\Payloads\SlackHttpPayloadServicePostMessage;
use SlackHttpService\SlackHttpService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handling incoming webhooks from Drupal.
 */
class Drupal extends DrupalBaseWebhook {

  /**
   * {@inheritdoc}
   */
  protected function trigger() {
    // Get the slack http service.
    $slack_http = new SlackHttpService();
    $slack = $slack_http->setAccessToken(Nuntius::getSettings()->getSetting('access_token'));

    // Build the attachment.
    $attachment = new SlackHttpPayloadServiceAttachments();
    $attachment
      ->setColor('#36a64f')
      ->setTitle($this->payload['title'])
      ->setTitleLink($this->url);

    if (!empty($this->payload['body'])) {
      $attachment->setText($this->payload['body']);
    }

    $attachments[] = $attachment;

    // Build the payload of the message.
    $message = new SlackHttpPayloadServicePostMessage();
    $message
      ->setChannel($this->slackRoom)
      ->setAttachments($attachments)
      ->setText('A new content on the site! Yay!');

    // Posting the message.
    $slack->Chat()->postMessage($message);

    return new Response();
  }

}
