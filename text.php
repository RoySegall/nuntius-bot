<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$buttons_template = $facebook
  ->templates
  ->airlineItinerary;

$buttons_template
  ->introMessage('You ticket')
  ->locale('he_IL')
  ->themeColor('#ff9900')
  ->pnrNumber('119900')
  ->passengerInfo(
    $facebook->templates->passengerInfo
      ->name('Roy Segall')
      ->ticketNumber('1')
      ->passengerId('22')
  )
  ->passengerInfo(
    $facebook->templates->passengerInfo
      ->reset()
      ->name('Noy Geva')
      ->ticketNumber('2')
      ->passengerId('54')
  )
  ->flightInfo(
    $facebook->templates->flightInfo
      ->flightNumber('345543')
      ->connectionId('2345')
      ->segmentId('90')
      ->aircraftType('Boing 747')
      ->departureAirport($facebook->templates->airport->terminal('Natbag')->city('Tel Aviv')->gate('D3')->airportCode('NATBAG'))
      ->arrivalAirport($facebook->templates->airport->terminal('Rome')->city('Rome')->gate('D34')->airportCode('ROME'))
      ->departureTime('2016-01-02T19:45')
      ->arrivalTime('2016-01-06T19:45')
      ->travelClass('business')
  )
  ->passengerSegmentInfo(
    $facebook->templates->passengerSegmentInfo
      ->segmentId('90')
      ->passengerId('22')
      ->seat('12A')
      ->seatType('Business')
  )
  ->passengerSegmentInfo(
    $facebook->templates->passengerSegmentInfo
      ->reset()
      ->segmentId('90')
      ->passengerId('54')
      ->seat('12B')
      ->seatType('Business')
      ->addProductInfo('Pizza', 'Corn')
  )
  ->priceInfo($facebook->templates->priceInfo->amount('22000')->currency('ILS')->title('Good!'))
  ->basePrice('22000')
  ->tax('10')
  ->totalPrice('22900')
  ->currency('ILS');

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
