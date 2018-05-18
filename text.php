<?php

require_once 'vendor/autoload.php';


$capsules = \Nuntius\Nuntius::getCapsuleManager();

//$capsules->enableCapsule('system');
\Kint::dump($capsules->getCapsuleImplementations('system', 'services'));
