<?php

require_once 'vendor/autoload.php';

$facebook = \Nuntius\Nuntius::facebookSendApi();

$buttons_template = $facebook->quickReplies
  ->text('select')
  ->addQuickReply($facebook->quickReply->contentType('text')->title('first')->payload('FIRST'))
  ->addQuickReply($facebook->quickReply->contentType('text')->title('first')->payload('FIRST'))
  ->addQuickReply($facebook->quickReply->contentType('text')->title('first')->payload('FIRST'))
  ->addQuickReply($facebook->quickReply->contentType('text')->title('first')->payload('FIRST'));

$facebook
  ->setRecipientId('')
  ->setAccessToken('');

$facebook->sendMessage($buttons_template);
