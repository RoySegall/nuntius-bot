<?php

namespace Nuntius\System\Constraints;

class NuntiusConstraint {

  protected $fields;

  /**
   * Check the field is OK.
   *
   * @param $field
   *
   * @param callable $callback
   */
  public function checkValue($field, callable $callback) {

    if (array_key_exists($field, $this->fields)) {
      $this->fields[$field] = [];
    }

  }

}
