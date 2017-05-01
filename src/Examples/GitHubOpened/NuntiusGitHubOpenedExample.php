<?php

namespace Nuntius\Examples\GitHubOpened;

use Nuntius\Dispatcher\GitHubEvent;
use Nuntius\Dispatcher\GitHubOpenedEventInterface;
use Nuntius\Nuntius;
use SlackHttpService\Payloads\SlackHttpPayloadServiceAttachments;
use SlackHttpService\Payloads\SlackHttpPayloadServicePostMessage;
use SlackHttpService\SlackHttpService;

class NuntiusGitHubOpenedExample implements GitHubOpenedEventInterface {

  /**
   * {@inheritdoc}
   */
  public function act(GitHubEvent $event) {
    // Build the info variable to the post message method.
    $info = $this->getPayload($event);

    $key = $info['key'];

    $info['text'] = 'Hi ' . $info['username'];
    $info['text'] .= $key == 'issue' ? ', You created an issue' : ', You created a PR';

    $info['footer'] = 'Created at ' . $info['created'];

    $this->postMessage($info);
  }

  /**
   * Process the event into a payload.
   *
   * @param GitHubEvent $event
   *   Object with GitHub event.
   *
   * @return array
   *   The payload.
   */
  protected function getPayload(GitHubEvent $event) {
    $data = $event->getData();

    $key = empty($data->pull_request) ? 'issue' : 'pull_request';

    // Build the info variable to the post message method.
    return [
      'image' => $data->{$key}->user->avatar_url,
      'username' => $data->{$key}->user->login,
      'url' => $data->{$key}->html_url,
      'title' => $data->{$key}->title,
      'body' => $data->{$key}->body,
      'created' => $data->{$key}->created_at,
      'key' => $key,
    ];
  }

  /**
   * Posting the message.
   *
   * @param $info
   *   Information relate to
   */
  protected function postMessage($info) {
    // Get the slack http service.
    $slack_http = new SlackHttpService();
    $slack = $slack_http->setAccessToken(Nuntius::getSettings()->getSetting('access_token'));

    // Get the IM room.
    $im_room = $slack->Im()->getImForUser($slack->Users()->getUserByName(strtolower($info['username'])));

    // Build the attachment.
    $attachment = new SlackHttpPayloadServiceAttachments();
    $attachment
      ->setColor('#36a64f')
      ->setTitle($info['title'])
      ->setTitleLink($info['url'])
      ->setText($info['body'])
      ->setThumbUrl($info['image'])
      ->setFooter($info['footer']);

    $attachments[] = $attachment;

    // Build the payload of the message.
    $message = new SlackHttpPayloadServicePostMessage();
    $message
      ->setChannel($im_room)
      ->setAttachments($attachments)
      ->setText($info['text']);

    // Posting the message.
    $slack->Chat()->postMessage($message);
  }

}