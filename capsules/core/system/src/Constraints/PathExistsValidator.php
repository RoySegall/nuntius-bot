<?php

namespace Nuntius\System\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PathExistsValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   */
  public function validate($value, Constraint $constraint) {

    $path = \Nuntius\Nuntius::getCapsuleManager()->getRoot() . '/' . $value;

    if (file_exists($path)) {
      return;
    }

    $this->context->buildViolation($constraint->message)
      ->setParameter('{{ value }}', $this->formatValue($path))
      ->addViolation();
  }
}
