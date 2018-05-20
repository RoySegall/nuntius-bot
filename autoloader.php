<?php

/**
 * Registering the custom namespaces.
 */

/** @var \Composer\Autoload\ClassLoader $composer */
$composer = require_once 'vendor/autoload.php';

try {
  $capsule_manager = \Nuntius\Nuntius::getCapsuleManager();

  // Get the enabled capsules.
  $enabled = $capsule_manager->capsuleList('enabled');

  // Get all the capsules.
  $capsules = $capsule_manager->getCapsules();

  foreach ($enabled as $enable) {
    $capsule = $capsules[$enable];
    $path = $capsules[$enable];
    $names = explode('_', $capsule['machine_name']);
    $namespace = 'Nuntius\\' . implode('', array_map(function($item) {
        return ucfirst($item);
      }, $names));

    $composer->addPsr4($namespace . '\\', $capsule_manager->getRoot() . '/capsules/core/system/src/');
  }
} catch (Exception $e) {
  
}

return $composer;
