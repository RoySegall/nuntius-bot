<?php

namespace Nuntius\Examples\Names;

use Symfony\Component\EventDispatcher\Event;

class NameEvent extends Event {

  /**
   * List of names.
   *
   * @var string[]
   */
  protected $names = [];

  /**
   * Adding a name to the list.
   *
   * @param string $name
   */
  public function addName($name) {
    $this->names[] = $name;
  }

  /**
   * Get all the names.
   *
   * @return string[]
   *   Get all the names.
   */
  public function getNames() {
    return $this->names;
  }

}
