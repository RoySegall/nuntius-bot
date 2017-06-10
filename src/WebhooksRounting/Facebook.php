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
//      $this->client->send($plugin->startTalking(), $channel);
      // todo: handle.
    }

    if (!$text = call_user_func_array([$plugin, $callback], $arguments)) {
      $text = '<b>asdasd</b>';
    }

    $text = '
    {
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
           {
            "title":"Welcome to Peters Hats",
            "image_url":"https://petersfancybrownhats.com/company_image.png",
            "subtitle":"Weve got the right hat for everyone.",
            "default_action": {
      "type": "web_url",
              "url": "https://peterssendreceiveapp.ngrok.io/view?item=103",
              "messenger_extensions": true,
              "webview_height_ratio": "tall",
              "fallback_url": "https://peterssendreceiveapp.ngrok.io/"
            },
            "buttons":[
              {
                "type":"web_url",
                "url":"https://petersfancybrownhats.com",
                "title":"View Website"
              },{
      "type":"postback",
                "title":"Start Chatting",
                "payload":"DEVELOPER_DEFINED_PAYLOAD"
              }              
            ]      
          }]
}
}
}';

    $options = [
      'form_params' => [
        'recipient' => [
          'id' => $fb_request['sender']
        ],
        'message' => (array)json_decode($text),
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
