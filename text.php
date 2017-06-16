<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$buttons_template = $facebook->senderAction->senderAction('TYPING_ON');

$options = [
  'form_params' => [
    'recipient' => [
      'id' => '1500215420053069',
    ],
    'sender_action' => 'mark_seen',
  ],
];

$access_token = 'EAABkfZBB2iyQBAB9zNjZBe6TRC34vNQXCZBYXPJvpVWXht7dsR4dNFYo3MT1iU1FhqtPZCXn7Cz0pevEuG0pWIOYrDQ0foUqhoYWZCEODzmzpzerXyXzRbLz53l0pnQUs3rFXdJD3pqapfeL64vgjgP9AY3Gx0DOp16GBQI53uwZDZD';
try {
  \Nuntius\Nuntius::getGuzzle()->post('https://graph.facebook.com/v2.6/me/messages?access_token=' . $access_token, $options);

} catch (\GuzzleHttp\Exception\ClientException $e) {
  Kint::dump($e->getResponse()->getBody()->getContents());
  return;
}

Kint::dump($buttons_template->getData());
