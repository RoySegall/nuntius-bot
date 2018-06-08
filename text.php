<?php

require_once 'autoloader.php';

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/** @var \Nuntius\System\Plugin\Entity\System $system */
$system = \Nuntius\System\System::getEntityManager()->createInstance('system');

$system->path = 'a';
$system->save();
