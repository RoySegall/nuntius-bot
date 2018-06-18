<?php

require_once 'autoloader.php';

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

\Nuntius\Nuntius::getCapsuleManager()->enableCapsule('capsule_test_secondary');

/** @var \Nuntius\System\Plugin\Entity\System $entity */
$entity = \Nuntius\System\System::getEntityManager()->createInstance('system');


$entity->name = 'Testing';
$entity->machine_name = 'testing';
$entity->description = 'testing entity';
$entity->path = '.';
$entity->status = 1;

$entity = $entity->save();
$entity->delete($entity->id);
