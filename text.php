<?php

require_once 'vendor/autoload.php';

\Nuntius\Nuntius::getEntityManager()->get('system')->update('updates', ['processed' => ['foo']]);