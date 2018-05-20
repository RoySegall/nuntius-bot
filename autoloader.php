<?php

/**
 * Registering the custom namespaces.
 */

/** @var \Composer\Autoload\ClassLoader $composer */
$composer = include getcwd() . '/vendor/autoload.php';

try {
  $capsule_manager = \Nuntius\Nuntius::getCapsuleManager();
  $capsule_manager->setAutoloader($composer);
  $capsule_manager->rebuildNamespaces();
} catch (Exception $e) {
  
}

return $composer;
