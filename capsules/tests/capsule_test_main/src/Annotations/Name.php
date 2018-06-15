<?php

namespace Nuntius\CapsuleTestMain\Annotations;

use Nuntius\System\Annotations\NuntiusAnnotation;
use Nuntius\System\Annotations\NuntiusAnnotationBase;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Name extends NuntiusAnnotationBase implements NuntiusAnnotation {

  public $name;

  public $age;

}
