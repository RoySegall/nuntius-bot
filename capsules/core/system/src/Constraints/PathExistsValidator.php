<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nuntius\System\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PathExistsValidator extends ConstraintValidator {
  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    $this->context->buildViolation($constraint->message)
      ->setParameter('{{ value }}', $this->formatValue($value))
      ->addViolation();
  }
}
