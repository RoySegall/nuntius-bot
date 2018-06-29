<?php

require_once 'autoloader.php';

/**
 * @param $type
 * @return \Nuntius\System\CacheBase
 * @throws \Nuntius\Capsule\CapsuleErrorException
 */
function get_cache($type) {
    return \Nuntius\System\System::getCacheManager()->createInstance($type);
}

function get_entity() {
    $entity = \Nuntius\System\System::getEntityManager()->createInstance('system');
    return $entity->loadMultiple(['0161dd3c-b8af-4d84-b1cf-114c5162363e', '0e3512e1-e02c-45a8-bfb3-cc5a24eb40d9']);
}


function benchmark1() {
    $cache = get_cache('memcache');
    $cache->get('list_entities');
}

function benchmark2() {
    $cache = get_cache('db');
    $cache->get('list_entities');
}

$time_start = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    benchmark1();
}

$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

echo "The time of execution is: {$execution_time}\n";