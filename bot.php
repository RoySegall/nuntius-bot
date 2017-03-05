<?php

require_once 'vendor/autoload.php';

use React\EventLoop\Factory;
use Mpociot\BotMan\BotManFactory;
use Mpociot\BotMan\BotMan;

$loop = Factory::create();
$botman = BotManFactory::createForRTM([
  'slack_token' => \Nuntius\Nuntius::getSettings()['bot_id'],
], $loop);

$botman->hears('Hello', function(BotMan $bot) {
  $bot->startConversation(new Nuntius\Plugins\OnboardingConversation());
});

$loop->run();
