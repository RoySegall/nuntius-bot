<?php

require_once 'vendor/autoload.php';

use PhpSlackBot\Bot;

$bot = new Bot();
// Get your token here https://my.slack.com/services/new/bot.
$bot->setToken('xoxb-122081602870-7snjp9RHBDrEZ7l9j47qfXH2');
$bot->loadCatchAllCommand(new \Nuntius\NuntiusSuperCommand());
$bot->run();
