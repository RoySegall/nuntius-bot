<?php

require_once 'vendor/autoload.php';

use PhpSlackBot\Bot;

// Setting Nuntius and the plugins.
$nuntius = new \Nuntius\Nuntius();
$nuntius->addPlugins(New \Nuntius\Plugins\Reminder());

$command = new \Nuntius\NuntiusSuperCommand();
$command->setNuntius($nuntius);

$bot = new Bot();

// Get your token here https://my.slack.com/services/new/bot.
$bot->setToken(\Nuntius\Nuntius::getSettings()['bot_id']);
$bot->loadCatchAllCommand($command);
$bot->run();
