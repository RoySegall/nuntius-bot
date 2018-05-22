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
        $commands = $capsule_manager->getCapsuleImplementations($capsule['machine_name'], 'commands');

        foreach ($commands as $command) {
            $application->add(new $command);
        }
    }
} catch (\Exception $e) {

}

$application->run();
