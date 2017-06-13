<?php

namespace Nuntius\WebhooksRounting;

use Nuntius\Nuntius;
use Nuntius\WebhooksRoutingControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handeling facebook bot.
 */
class Facebook implements WebhooksRoutingControllerInterface {

  protected $accessToken;

  protected $fbRquest;

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
    $this->fbRquest = $this->extractFacebookRequest(json_decode(file_get_contents("php://input")));

    if (empty($fb_request['text'])) {

      if (!empty($fb_request['postback'])) {
        $this->sendMessage($this->helpRouter());
      }

      return new Response();
    }

    $task_info = Nuntius::getTasksManager()->getMatchingTask($fb_request['text']);

    list($plugin, $callback, $arguments) = $task_info;

    if (!$text = call_user_func_array([$plugin, $callback], $arguments)) {
      $text = "Hmm.... Sorry, I can't find something to tell you. Try something else, mate.";
    }

    $this->sendMessage($text);

    return new Response();
  }

  protected function sendMessage($text) {
    $message = !is_array($text) ? $message = ['text' => $text] : $text;

    $options = [
      'form_params' => [
        'recipient' => [
          'id' => $this->fbRquest['sender'],
        ],
        'message' => $message,
      ],
    ];

    Nuntius::getGuzzle()->post('https://graph.facebook.com/v2.6/me/messages?access_token=' . $this->accessToken, $options);
  }

  protected function extractFacebookRequest(\stdClass $request) {
    $payload = $request->entry[0];
    $message = $payload->messaging[0];

    $payload = [
      'id' => $payload->id,
      'time' => $payload->time,
      'sender' => $message->sender->id,
      'recipient' => $message->recipient->id,
      'text' => $message->message->text,
      'mid' => $message->message->mid,
      'seq' => $message->message->seq,
    ];

    if (!empty($message->postback)) {
      // This is a post back button. Add it to the payload variable.
      $payload['postback'] = $message->postback->payload;
    }

    return $payload;
  }

  protected function getSenderInfo($id, $fields = 'first_name,last_name') {
    return json_decode(Nuntius::getGuzzle()->get('https://graph.facebook.com/v2.6/' . $id, [
      'query' => [
        'access_token' => $this->accessToken,
        'fields' => $fields,
      ],
    ])->getBody());
  }

  protected function helpRouter() {
    switch ($this->fbRquest['postback']) {
      case 'something_nice':
        $texts = [
          'You look lovely!',
          'Usually you wakes up looking good. Today, you took it to the next level!',
          'Hey there POTUS... sorry! thought you are some one else...',
        ];

        shuffle($texts);
        return reset($texts);

      case 'what_is_my_name':
        $info = $this->getSenderInfo($this->fbRquest['sender']);
        return 'You are ' . $info->first_name . ' ' . $info->last_name . ', in case you forgot';

      case 'toss_a_coin':
        $options = ['heads', 'tail'];
        shuffle($options);
        $result = reset($options);

        return "Tossing.... it's " . $result;
    }
  }

}
