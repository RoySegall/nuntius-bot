<?php

require_once 'autoloader.php';

/** @var \Nuntius\System\Plugin\Cache\Memcache $cache */
//$cache = \Nuntius\System\System::getCacheManager()->createInstance('memcache');
//
//d($cache->get('a'));

$foo = \Nuntius\System\System::getEntityManager()->createInstance('system')->load('0161dd3c-b8af-4d84-b1cf-114c5162363e');

$ser = serialize($foo);

$unser = unserialize($ser);

d($unser);