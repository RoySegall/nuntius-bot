<?php

require_once 'autoloader.php';

$time_start = microtime(true);

try {

//  \Nuntius\Nuntius::getCapsuleManager()->enableCapsule('system');

  /** @var \Nuntius\System\Plugin\Cache\DBCache $cache */
  $cache = \Nuntius\System\System::getCacheManager()->createInstance('db');

  /** @var \Nuntius\System\Plugin\Entity\System $entity */
  $entity = \Nuntius\System\System::getEntityManager()->createInstance('system');

  if (!$cache_results = $cache->get('entities')) {
//    for ($i = 0; $i <= 10; $i++) {
//      $entity->name = 'Testing';
//      $entity->machine_name = 'testing';
//      $entity->description = 'testing entity';
//      $entity->path = '.';
//      $entity->status = 1;
//      $entity->save();
//    }

    $cache_results = $cache->set('entities', $entity->loadMultiple());
  }

  $cache->clear();
} catch (Exception $e) {
  d($e->getMessage());
}

$time_end = microtime(true);

$execution_time = ($time_end - $time_start)/60;

echo "The time of execution is: {$execution_time}\n";