<?php

require_once 'autoloader.php';

$hooks = \Nuntius\System\System::hooksDispatcher();
$hooks->invoke('entity_create');
