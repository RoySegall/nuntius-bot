<?php

/**
 * Registering the custom namespaces.
 */

/** @var \Composer\Autoload\ClassLoader $composer */
$composer = include getcwd() . '/vendor/autoload.php';

spl_autoload_register('nuntius_spl');

function nuntius_spl($class) {

  if (strpos($class, 'Nuntius') !== 0) {
    // Not a nuntius namespace. skip.
    return;
  }

  // Get the capsule manager.
  $capsule_manager = \Nuntius\Nuntius::getCapsuleManager();

  // Building the variables which will help us construct the path.
  $namepsaces = explode('\\', $class);
  $subfolders = array_splice($namepsaces,2);

  // Taking a camel case to a lower case: Foo to foo and FooBar to foo_bar.
  $change_namespace = function ($camel) {
    $snake = preg_replace('/[A-Z]/', '_$0', $camel);
    $snake = strtolower($snake);
    $snake = ltrim($snake, '_');
    return $snake;
  };
  $capsule = $change_namespace($namepsaces[1]);

  // Going over the capsules for bootstrapping by the weight in the DB. We need
  // it in order to keep our dependencies tree.
  foreach ($capsule_manager->getCapsulesForBootstrapping() as $info) {

    if ($capsule != $info['machine_name']) {
      // This is the current capsule namespace we need to handle.
      continue;
    }

    // Building the path.
    $include = $capsule_manager->getRoot() . '/' . $info['path'] . '/src/' . implode('/', $subfolders) . '.php';

    if (file_exists($include)) {
      require_once $include;
    }
  }
}

return $composer;
