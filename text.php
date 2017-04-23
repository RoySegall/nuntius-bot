<?php

require_once 'vendor/autoload.php';

$dispatcher = \Nuntius\Nuntius::getDispatcher();
$dispatcher->buildDispatcher();
Kint::dump($dispatcher->dispatch('names')->getNames());
