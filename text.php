<?php

require_once 'vendor/autoload.php';

print_r(fnmatch('@nuntius when @orit is logged in tell him to check about the tahini', '@nuntius when [*] is logged in tell him to [*]'));

$nuntius = new \Nuntius\Nuntius();
$nuntius->addPlugins(New \Nuntius\Plugins\Reminder());

$nuntius->getPlugin('@nuntius when @orit is logged in tell him to check about the tahini');
