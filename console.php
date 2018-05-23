<?php

require 'autoloader.php';

use Symfony\Component\Console\Application;

$application = new Application();

$commands = \Nuntius\Nuntius::getSettings()->getSetting('commands');
foreach ($commands as $namespace) {
  $application->add(new $namespace);
}

// Register other capsules commands. Wrapping it with a try in case the system
// is not installed.
try {
  $capsule_manager = \Nuntius\Nuntius::getCapsuleManager();

  foreach ($capsule_manager->getCapsulesForBootstrapping() as $capsule) {
    $services = $capsule_manager->getCapsuleImplementations($capsule['machine_name'], 'services');

    foreach ($services as $id => $service) {
      if (empty($service['command'])) {
        continue;
      }

      $application->add(\Nuntius\Nuntius::container()->get($id));
    }

  }
} catch (\Exception $e) {

}

$application->run();
