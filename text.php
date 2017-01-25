<?php

use Nuntius\Nuntius;

require_once 'vendor/autoload.php';

$nuntius = new Nuntius();
$rethinkdb = Nuntius::getRethinkDB();

$results = Nuntius::getRethinkDB()
  ->getTable('users')
  ->filter(\r\row('username')->eq('roysegall'))
  ->filter(\r\row('greeted')->eq(TRUE))
  ->run(Nuntius::getRethinkDB()->getConnection());

if (!$results->toArray()) {
  var_dump('hello');
  Nuntius::getRethinkDB()->addEntry('users', [
    'username' => 'roysegall',
    'greeted' => TRUE,
  ]);
}
