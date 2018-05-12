<?php

require_once 'vendor/autoload.php';

$db = \Nuntius\Nuntius::getDb();

//// Create a random table.
//if (!$db->getOperations()->tableExists('superheroes')) {
//  $db->getOperations()->tableCreate('superheroes');
//}
//
//$objects = [
//  ['name' => 'Tony', 'age' => 27, 'alterego' => 'Iron Man'],
//  ['name' => 'Peter', 'age' => 20, 'alterego' => 'SpiderMan'],
//  ['name' => 'Steve', 'age' => 18, 'alterego' => 'Captain America'],
//];

//$db->getOperations()->tableCreate('superheroes');
//$db->getOperations()->tableDrop('superheroes');

//$id = '5ae5f2d2f3dd2b68fc5929a2';
$entityManager = \Nuntius\Nuntius::getEntityManager();

$updates = $entityManager->get('system')->load('updates');


if (empty($updates->processed)) {
  $updates->processed = [];
}

$processed = $updates->processed;
$processed[] = 'f';
$entityManager->get('system')->update(['id' => 'updates', 'processed' => []]);
