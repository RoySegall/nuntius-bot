<?php

require_once 'vendor/autoload.php';

$db = \Nuntius\Nuntius::getDb();

$entry = $db->getStorage()->table('context')->deleteMultiple();
Kint::dump($entry);

