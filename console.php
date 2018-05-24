<?php

require 'autoloader.php';

use Symfony\Component\Console\Application;

$application = new Application();

\Nuntius\Nuntius::addCommands($application);

$application->run();
