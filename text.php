<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$text = $facebook->contentType->audio->url('https://petersapparel.com/bin/clip.mp3');

Kint::dump($text->getData());