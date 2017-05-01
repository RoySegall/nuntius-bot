<?php

namespace SlackHttpService\Services;

use SlackHttpService\Payloads\SlackHttpPayloadServicePostMessage;

/**
 * Handle the chat part of the rest api with slack.
 */
class SlackHttpServiceChat extends SlackHttpServiceHandlerAbstract {

  /**
   * {@inheritdoc}
   */
  protected $mainApi = 'chat';

  /**
   * Posting a message to a room.
   *
   * @param SlackHttpPayloadServicePostMessage $message
   *   The post message payload object.
   *
   * @return \stdClass
   *   The JSON representation of the user list request.
   */
  public function postMessage(SlackHttpPayloadServicePostMessage $message) {
    return $this->decodeRequest($this->slackHttpService->requestWithArguments($this->mainApi . '.' . 'postMessage', $message->getPayload()));
  }

}
