<?php

require_once 'autoloader.php';

if (!\Nuntius\Nuntius::getCapsuleManager()->capsuleEnabled('system')) {
  \Nuntius\Nuntius::getCapsuleManager()->enableCapsule('system');
}
$foo = new \Nuntius\System\PluginDispatcher();
$foo = new \Nuntius\System\Hooks\EntityCreate();
