<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$buttons_template = $facebook
  ->templates
  ->airlineCheckIn;

$buttons_template->introMessage('You ticket')
  ->locale('he_IL')
  ->themeColor('#ff9900')
  ->introMessage('Hey!')
  ->pnrNumber('110090')
  ->flightNumber('334455')
  ->departureAirport($facebook->templates->airport->airportCode('Roy')->city('RamatGan')->gate('3')->terminal(3))
  ->arrivalAirport($facebook->templates->airport->airportCode('Noy')->city('Ranana'))
  ->boardingTime('2016-01-02T19:05')
  ->departureTime('2016-01-03T19:05')
  ->arrivalTime('2016-01-03T19:05')
->checkinUrl('http://mako.co.il');

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
