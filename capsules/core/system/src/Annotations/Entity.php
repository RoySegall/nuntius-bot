<?php

namespace Nuntius\System\Annotations;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Entity implements NuntiusAnnotation {

  public $id;

  public $properties;

  public $relations;

}
