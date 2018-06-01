<?php

require_once 'autoloader.php';

/** @var \Nuntius\System\Plugin\Entity\System $system */
$system = \Nuntius\System\System::getEntityManager()->createInstance('system');

\Kint::dump($system->load('810cd520-52fc-4a94-b4a8-9d5f9a4c8816'));
