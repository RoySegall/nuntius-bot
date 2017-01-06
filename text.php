<?php

require_once 'vendor/autoload.php';

$nuntius = new \Nuntius\Nuntius();
$nuntius->addPlugins(New \Nuntius\Plugins\Reminder());

$nuntius
  ->getPlugin('@nuntius delete all the reminders I asked from you');
