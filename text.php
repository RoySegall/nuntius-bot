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
//$vid = array_keys($vocabularies)[0];

$tag->name = 'bmw';
$tag->description = '';
$tag->vocabulary = "aaa";

$tag->validate();

