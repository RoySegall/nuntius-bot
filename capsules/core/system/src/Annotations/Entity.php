<?php

namespace Nuntius\System\Annotations;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Entity extends NuntiusAnnotationBase implements NuntiusAnnotation {

  public $properties;

  public $relations;

}
