<?php

require 'vendor/autoload.php';

$value = \Nuntius\Nuntius::getSettings();
$db = \Nuntius\Nuntius::getRethinkDB();

$db->createDB($value['rethinkdb']['db']);
print("The DB was created.\n");

sleep(5);

foreach ($value['schemes'] as $scheme) {
  $db->createTable($scheme);
  print("The table {$scheme} has created\n");
}
