<?php

require_once 'autoloader.php';

$foo = \Nuntius\System\System::getCacheManager();
/** @var \Nuntius\System\Plugin\Cache\DBCache $cache */
$cache = $foo->createInstance('db');

if (!$results = $cache->get('names')) {
  $results = $cache->set('names', ['noy', 'roy', 'tom']);
}

d($results);

//$cache->clear();

