<?php

namespace Nuntius\WebhooksRounting;

use Nuntius\FacebookSendApi\SendAPITransform;
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
   * {@inheritdoc}
   */
  public function response(Request $request) {

    if (!empty($_GET['hub_challenge'])) {
      // Validating facebook testing request.
      return new Response($_GET['hub_challenge']);
    }

    Nuntius::getContextManager()->setContext('facebook');

    $this->accessToken = Nuntius::getSettings()->getSetting('fb_token');
    $this->fbRequest = $this->extractFacebookRequest(json_decode(file_get_contents("php://input")));

    if (empty($this->fbRequest['text'])) {

      if (!empty($this->fbRequest['postback'])) {
        $this->sendMessage($this->helpRouter());
      }

      return new Response();
    }

    $task_info = Nuntius::getTasksManager()->getMatchingTask($this->fbRequest['text']);

    list($plugin, $callback, $arguments) = $task_info;

    if (!$text = call_user_func_array([$plugin, $callback], $arguments)) {
      $text = "Hmm.... Sorry, I can't find something to tell you. Try something else, mate.";
    }

    $facebook = new \Nuntius\FacebookSendApi\SendAPI();

    $buttons_template = $facebook
      ->templates
      ->receipt;

    $buttons_template
      ->merchantName('Ovad')
      ->recipientName('Roy Segall')
      ->currency('ILS')
      ->paymentMethod('cache')
      ->orderNumber('mako.co.il')
      ->totalCost('30')
      ->addElement(
        $facebook->templates->receiptElement
          ->title('Sabich')
          ->subtitle('Salad, Egg, Eggplant, Hummos')
          ->price(25)
          ->quantity(1)
          ->currency('ILS')
          ->imageUrl('https://images1.ynet.co.il/PicServer2/13062011/3366449/2wa.jpg')
      )
      ->addElement(
        $facebook->templates->receiptElement
          ->reset()
          ->title('Grapes')
          ->price(5)
          ->quantity(1)
          ->currency('ILS')
          ->imageUrl('http://www.burgerking.co.il/Uploads/Product%20Images/GrapeJuiceBottle.jpg')
      )
      ->street1('Harav levin')
      ->city('Ramat gan')
      ->state('Israel')
      ->country('IL')
      ->postalCode('52260')
    ->addAdjustment('Hate eggplant', 20)
    ->addAdjustment('First timer', 10);

    $this->sendMessage($buttons_template);

    return new Response();
  }

  /**
   * Sending a message.
   *
   * @param array|string $text
   *   The text is self or an array matching the send API.
   */
  protected function sendMessage($text) {
    if ($text instanceof SendAPITransform) {
      $message = $text->getData();
    }
    else {
      $message = !is_array($text) ? $message = ['text' => $text] : $text;
    }

    $options = [
      'form_params' => [
        'recipient' => [
          'id' => $this->fbRequest['sender'],
        ],
        'message' => $message,
      ],
    ];

    Nuntius::getGuzzle()->post('https://graph.facebook.com/v2.6/me/messages?access_token=' . $this->accessToken, $options);
  }

  /**
   * Extracting information from the request.
   *
   * @param \stdClass $request
   *   The request object.
   *
   * @return array
   *   Array of the request.
   */
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
