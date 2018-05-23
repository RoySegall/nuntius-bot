<?php

require_once 'autoloader.php';

$capsule_manager = \Nuntius\Nuntius::getCapsuleManager();

foreach ($capsule_manager->getCapsulesForBootstrapping() as $capsule) {
  $services = $capsule_manager->getCapsuleImplementations($capsule['machine_name'], 'services');

  foreach ($services as $id => $service) {
    if (empty($service['command'])) {
      continue;
    }
    \Kint::dump(\Nuntius\Nuntius::container()->get($id));
  }

}
