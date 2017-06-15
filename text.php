<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$buttons_template = $facebook
  ->templates
  ->airlineFlightUpdate;

$buttons_template
  ->introMessage('You ticket')
  ->updateType('CANCELLATION')
  ->locale('he_IL')
  ->themeColor('#ff9900')
  ->pnrNumber('119900')
  ->updateFlightInfo(
    $facebook->templates->updateFlightInfo
      ->flightNumber('190')
      ->departureAirport($facebook->templates->airport->airportCode('NATBAG')->city('TLV')->terminal(3)->gate(20))
      ->arrivalAirport($facebook->templates->airport->airportCode('ROME')->city('Rome')->terminal(2)->gate('6D'))
      ->boardingTime('2015-12-26T10:30')
      ->departureTime('2015-12-26T13:30')
      ->arrivalTime('2015-12-27T10:30')
  );

$options = [
  'form_params' => [
    'recipient' => [
      'id' => '1500215420053069',
    ],
    'message' => $buttons_template->getData(),
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
