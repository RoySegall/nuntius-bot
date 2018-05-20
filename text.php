<?php

require_once 'autoloader.php';

//\Nuntius\Nuntius::getCapsuleManager()->enableCapsule('system');
$foo = new \Nuntius\System\PluginDispatcher();

\Kint::dump($foo);

