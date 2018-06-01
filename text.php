<?php

require_once 'autoloader.php';

$plugin_manager = \Nuntius\Nuntius::container()->get('plugin_manager');
$entity_manager = \Nuntius\Nuntius::container()->get('entity.plugin_manager');

$annotations = new \Nuntius\System\Annotations\Entity();
$plugins = $plugin_manager->getPlugins('Plugin\Entity', $annotations);

\Kint::dump($plugins);

//\Nuntius\Nuntius::getCapsuleManager()->enableCapsule('capsule_test_secondary');