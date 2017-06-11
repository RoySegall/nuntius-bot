<?php

namespace Nuntius\WebhooksRounting;

use Nuntius\Nuntius;
use Nuntius\TaskConversationInterface;
use Nuntius\WebhooksRoutingControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handeling facebook bot.
 */
class Facebook implements WebhooksRoutingControllerInterface {

  protected $accessToken;

  /**
   * {@inheritdoc}
   */
  public function response(Request $request) {

    if (!empty($_GET['hub_challenge'])) {
      // Validating facebook testing request.
      return new Response($_GET['hub_challenge']);
    }

    Nuntius::getContextManager()->setContext('facebook');

    $this->accessToken = Nuntius::getSettings()->getSetting('fb_token');
    $fb_request = $this->extractFacebookRequest(json_decode(file_get_contents("php://input")));

    if (empty($fb_request['text'])) {
      return new Response();
    }

    $task_info = Nuntius::getTasksManager()->getMatchingTask($fb_request['text']);

    $sender_info = $this->getSenderInfo($fb_request['sender']);
    list($plugin, $callback, $arguments) = $task_info;

    if ($plugin instanceof TaskConversationInterface) {
      // todo: Handle task conversation.
      // $this->client->send($plugin->startTalking(), $channel);
    }

    if (!$text = call_user_func_array([$plugin, $callback], $arguments)) {
      $text = "Hmm.... Sorry, I can't find something to tell you. Try something else, mate.";
    }

    $message = !is_array($text) ? $message = ['text' => $text] : $text;

    $options = [
      'form_params' => [
        'recipient' => [
          'id' => $fb_request['sender']
        ],
        'message' => $message,
      ],
    ];

    Nuntius::getGuzzle()->post('https://graph.facebook.com/v2.6/me/messages?access_token=' . $this->accessToken, $options);

  }

  protected function extractFacebookRequest(\stdClass $request) {
    $payload = $request->entry[0];
    $message = $payload->messaging[0];

    return [
      'id' => $payload->id,
      'time' => $payload->time,
      'sender' => $message->sender->id,
      'recipient' => $message->recipient->id,
      'text' => $message->message->text,
      'mid' => $message->message->mid,
      'seq' => $message->message->seq,
    ];
  }

  protected function getSenderInfo($id) {
    return json_decode(Nuntius::getGuzzle()->get('https://graph.facebook.com/v2.6/' . $id, [
      'query' => [
        'access_token' => $this->accessToken,
        'fields' => 'first_name,last_name',
      ],
    ])->getBody());
  }

}
