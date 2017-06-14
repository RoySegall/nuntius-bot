<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$buttons_template = $facebook
  ->templates
  ->openGraph
  ->url('https://open.spotify.com/track/7GhIk7Il098yCjg4BQjzvb')
  ->button($facebook->buttons->url->title('foo')->url('mako.co.il'));

Kint::dump($buttons_template->getData());
