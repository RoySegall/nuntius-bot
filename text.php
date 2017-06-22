<?php

require_once 'vendor/autoload.php';

$send_api = \Nuntius\Nuntius::facebookSendApi();

$send_api
  ->setRecipientId(0)
  ->setAccessToken('');

$payload = $send_api->templates->button
  ->text('hey there! This is the default help response ' .
    'You can try this one and override it later on. ' .
    'Hope you will get some ideas :)')
  ->addButton($send_api->buttons->postBack->title('Say something nice')->payload('something_nice'))
  ->addButton($send_api->buttons->postBack->title("What's my name?")->payload('what_is_my_name'))
  ->addButton($send_api->buttons->postBack->title('Toss a coin?')->payload('toss_a_coin'));

$send_api->sendMessage($payload);
