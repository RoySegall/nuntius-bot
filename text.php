<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$buttons_template = $facebook
  ->templates
  ->button->text('Send me a message');

$buttons_template->addButton($facebook->buttons->url->title('foo')->url('http://google.com'));
$buttons_template->addButton($facebook->buttons->postBack->title('foo')->payload('FOO'));
$buttons_template->addButton($facebook->buttons->postBack->title('foo')->payload('FOO'));

Kint::dump($buttons_template->getData());
