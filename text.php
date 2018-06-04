<?php

require_once 'autoloader.php';

/** @var \Nuntius\System\Plugin\Entity\System $system */
$system = \Nuntius\System\System::getEntityManager()->createInstance('system');

$record = $system->load('f4763bc5-9637-414b-abc2-ae9edc315850');
\Kint::dump($record);


$record->description = 'tom';
$record = $record->save();
\Kint::dump($record);

