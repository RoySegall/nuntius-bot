<?php

require_once 'vendor/autoload.php';

$nuntius = new \Nuntius\Nuntius();
$nuntius->addPlugins(New \Nuntius\Plugins\Reminder());
$nuntius->addPlugins(New \Nuntius\Plugins\SmallTalk());
$nuntius->addPlugins(New \Nuntius\Plugins\Help());

$text = $nuntius
  ->getPlugin('what can do in Reminders');

var_dump($text);
