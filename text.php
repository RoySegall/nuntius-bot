<?php

require_once 'vendor/autoload.php';

$dispatcher = \Nuntius\Nuntius::getDispatcher();

$dispatcher->dispatch('github_opened', ['bar' => 'foo']);
