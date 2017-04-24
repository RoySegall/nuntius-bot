<?php

require_once 'vendor/autoload.php';

$container = \Nuntius\Nuntius::container();

Kint::dump(\Nuntius\Nuntius::getSettings()->getSetting('access_token'));
