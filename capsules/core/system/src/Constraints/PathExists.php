<?php

namespace Nuntius\System\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 */
class PathExists extends Constraint {
  public $message = 'The {{ value }} does not exists.';
}
