<?php

require_once 'vendor/autoload.php';


$capsules = \Nuntius\Nuntius::getCapsuleManager();

//$capsules->enableCapsule('system');
//$capsules->enableCapsule('message');
\Kint::dump($capsules->getCapsulesForBootstrapping());
