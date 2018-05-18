<?php

require_once 'vendor/autoload.php';


$capsules = \Nuntius\Nuntius::getCapsuleManager();

//$capsules->enableCapsule('system');
\Kint::dump($capsules->capsuleList('enabled'));
