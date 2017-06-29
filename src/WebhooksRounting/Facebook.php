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

    return $this->engage();
  }

  /**
   * Contains the logic for engaging with people using the Messenger platform.
   *
   * The logic for bootstrapping is done in the response method. Since most of
   * the people will override the class they might want only the override the
   * business logic and not the bootstrapping logic as well.
   *
   * @return Response
   */
  protected function engage() {
    if (empty($this->fbRequest['text'])) {

      if (!empty($this->fbRequest['postback'])) {
        $postbacks = Nuntius::getFbPostBackManager();

        if ($help_router = $postbacks->getPostBack($this->fbRequest['postback'])) {
          $help_router->setFbRequest($this->fbRequest);
          $this->sendAPI->sendMessage($help_router->postBack());
        }
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

}
