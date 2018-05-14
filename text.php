<?php

require_once 'vendor/autoload.php';


$capsules = \Nuntius\Nuntius::getCapsuleManager();

\Kint::dump($capsules->getCapsules());