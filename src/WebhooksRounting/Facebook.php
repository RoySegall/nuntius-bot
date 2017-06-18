<?php

namespace Nuntius\WebhooksRounting;

use Nuntius\Nuntius;
use Nuntius\WebhooksRoutingControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handling facebook bot.
 */
class Facebook implements WebhooksRoutingControllerInterface {

  /**
   * The access token of Facebook.
   *
   * @var string
   */
  protected $accessToken;

  /**
   * The Facebook request array information including the post back button.
   *
   * @var array
   */
  protected $fbRequest;

  /**
   * @var \FacebookMessengerSendApi\SendAPI
   */
  protected $sendAPI;

  /**
   * Facebook constructor.
   */
  function __construct() {
    $this->sendAPI = Nuntius::facebookSendApi();
  }

  /**
   * {@inheritdoc}
   */
  public function response(Request $request) {

    if (!empty($_GET['hub_challenge'])) {
      // Validating facebook testing request.
      return new Response($_GET['hub_challenge']);
    }

    Nuntius::getContextManager()->setContext('facebook');

    $this->fbRequest = $this->extractFacebookRequest(json_decode(file_get_contents("php://input")));
    $this->accessToken = Nuntius::getSettings()->getSetting('fb_token');

    $this->sendAPI
      ->setRecipientId($this->fbRequest['sender'])
      ->setAccessToken($this->accessToken);

    if (empty($this->fbRequest['text'])) {

      if (!empty($this->fbRequest['postback'])) {
        $this->sendAPI->sendMessage($this->helpRouter());
      }

      return new Response();
    }

    $task_info = Nuntius::getTasksManager()->getMatchingTask($this->fbRequest['text']);

    list($plugin, $callback, $arguments) = $task_info;

    if (empty($plugin)) {
      $text = "Hmm.... Sorry, I can't find something to tell you. Try something else, mate.";
    }
    else {
      $text = call_user_func_array([$plugin, $callback], $arguments);
    }

    $this->sendAPI->sendMessage($text);

    return new Response();
  }

  /**
   * Extracting information from the request.
   *
   * @param $request
   *   The request object.
   *
   * @return array
   *   Array of the request.
   */
  protected function extractFacebookRequest($request) {
    if (empty($request)) {
      return [];
    }

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

  /**
   * Get the sender information.
   *
   * @param $id
   *   The ID of the user.
   * @param string $fields
   *   The fields we desire to retrieve. Default to first and last name. The
   *   fields separated by comma.
   *
   * @return mixed
   */
  protected function getSenderInfo($id, $fields = 'first_name,last_name') {
    return json_decode(Nuntius::getGuzzle()->get('https://graph.facebook.com/v2.6/' . $id, [
      'query' => [
        'access_token' => $this->accessToken,
        'fields' => $fields,
      ],
    ])->getBody());
  }

  /**
   * Return an answer according to the postback button.
   *
   * @return string
   *   The string to return to the user.
   */
  protected function helpRouter() {
    switch ($this->fbRequest['postback']) {
      case 'something_nice':
        $texts = [
          'You look lovely!',
          'Usually you wakes up looking good. Today, you took it to the next level!',
          'Hey there POTUS... sorry! thought you are some one else...',
        ];

        shuffle($texts);
        return reset($texts);

      case 'what_is_my_name':
        $info = $this->getSenderInfo($this->fbRequest['sender']);
        return 'You are ' . $info->first_name . ' ' . $info->last_name . ', in case you forgot';

      case 'toss_a_coin':
        $options = ['heads', 'tail'];
        shuffle($options);
        $result = reset($options);

        return "Tossing.... it's " . $result;
    }
  }

}
