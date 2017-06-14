<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$text = $facebook->buttons->buy->title('a')->currency('ILS')->merchantName('ovad');

Kint::dump($text->getData());
