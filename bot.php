<?php

require_once 'vendor/autoload.php';

use PhpSlackBot\Bot;

$bot = new Bot();

// Get your token here https://my.slack.com/services/new/bot.
$bot->setToken(\Nuntius\Nuntius::getSettings()['bot_id']);
$bot->loadCatchAllCommand(new \Nuntius\NuntiusSuperCommand());
$bot->run();
