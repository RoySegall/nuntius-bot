<?php

require 'vendor/autoload.php';

$value = \Nuntius\Nuntius::getSettings();
$db = \Nuntius\Nuntius::getRethinkDB();

$db->createDB($value['rethinkdb']['db']);

print("The DB was created\n");

foreach ($value['schemes'] as $scheme) {
  $db->createTable($scheme);
  print("The {$scheme} was created\n");
}
