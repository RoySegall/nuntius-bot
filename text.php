<?php

require_once 'vendor/autoload.php';


$capsules = \Nuntius\Nuntius::getCapsuleManager();

\Kint::dump($capsules->getCapsules());

$foo = new \Symfony\Component\Finder\Finder();