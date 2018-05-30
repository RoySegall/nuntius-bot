<?php

require_once 'autoloader.php';

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Entity {

  public $id;
  public $properties;

}

/**
 * @Entity(
 *  id = "user",
 *  properties = {
 *   "id",
 *   "name",
 *   "password",
 *   "email"
 *  },
 * )
 */
class Testing {
}

$reflectionClass = new ReflectionClass(Testing::class);

$reader = new AnnotationReader();
$foo = $reader->getClassAnnotation($reflectionClass, 'entity');

\Kint::dump($foo);
