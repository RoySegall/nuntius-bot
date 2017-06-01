<?php

require_once 'vendor/autoload.php';


\Kint::dump(\Nuntius\Nuntius::getEntityManager()->get('system')->load('updates')->processed);