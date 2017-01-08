<?php

require_once 'vendor/autoload.php';

$nuntius = new \Nuntius\Nuntius();
$nuntius->addPlugins(New \Nuntius\Plugins\Reminder());
$nuntius->addPlugins(New \Nuntius\Plugins\SmallTalk());

$text = $nuntius
  ->getPlugin('hi');

var_dump($text);
