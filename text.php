<?php

require_once 'autoloader.php';

$foo = \Nuntius\System\System::getCacheManager();

d($foo->getCacheList());

