<?php

use Nuntius\Nuntius;

require_once 'vendor/autoload.php';

$nuntius = new Nuntius();
$foo = $nuntius->getPlugin('@nuntius what can you do in Reminders');
\Kint::dump($foo);
