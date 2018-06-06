<?php

require_once 'autoloader.php';

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


///** @var \Nuntius\System\Plugin\Entity\System $system */
$system = \Nuntius\System\System::getEntityManager()->createInstance('system');
//
d($system->validate());


//$validator = Validation::createValidator();
//if (0 !== count($violations)) {
//  // there are errors, now you can show them
//  foreach ($violations as $violation) {
//    echo $violation->getMessage().'<br>';
//  }
//}
