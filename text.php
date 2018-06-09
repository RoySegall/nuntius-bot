<?php

require_once 'autoloader.php';

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/** @var \Nuntius\CapsuleTestMain\Plugin\Entity\Vocabulary $vocabulary */
$vocabulary = \Nuntius\System\System::getEntityManager()->createInstance('vocabulary');

/** @var \Nuntius\CapsuleTestMain\Plugin\Entity\Tag $tag */
$tag = \Nuntius\System\System::getEntityManager()->createInstance('tag');

//if (!$vocabularies = $vocabulary->loadMultiple()) {
//  $vocabulary->name = 'Cars';
//  $vocabulary->description = 'List of cars';
//  $vocabulary->save();
//
//  $vocabularies = $vocabulary->loadMultiple();
//}
//
//$vid = array_keys($vocabularies);
//$tag->name = 'bmw';
//$tag->description = '';
//$tag->vocabulary = '5f4831cd-3217-40df-957e-dbfd2602360c';

//d($vid);
d($tag->load('d97adec0-6e8b-45b1-be9d-2a3a9249c54e', FALSE));

