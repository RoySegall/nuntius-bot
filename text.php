<?php

require_once 'autoloader.php';

$plugin_manager = \Nuntius\Nuntius::container()->get('plugin_manager');
$entity_manager = \Nuntius\Nuntius::container()->get('entity.plugin_manager');

\Kint::dump($plugin_manager->getPlugins('Plugin\Entity'));
//
//\Nuntius\Nuntius::getCapsuleManager()->enableCapsule('capsule_test_secondary');