<?php

require_once 'vendor/autoload.php';

$db = \Nuntius\Nuntius::getDb();

$entry = $db->getStorage()->table('logger')->loadMultiple(['f6ab1aa0-b8c7-4dfa-ada4-f640c6689979', 'bb091d04-1ee0-4459-bd42-bb8f4e936018']);
Kint::dump($entry);

