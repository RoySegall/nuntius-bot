<?php

require_once 'autoloader.php';

/** @var \Nuntius\System\Plugin\Cache\Memcache $cache */
$cache = \Nuntius\System\System::getCacheManager()->createInstance('memcache');

d($cache->get('a'));