<?php

require_once 'vendor/autoload.php';


$capsules = \Nuntius\Nuntius::getCapsuleManager();

$capsules->enableCapsule('systesdsm');
