<?php

require_once 'vendor/autoload.php';

$db = \Nuntius\Nuntius::getDb();

Kint::dump($db->getStorage());

