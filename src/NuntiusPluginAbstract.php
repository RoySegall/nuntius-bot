<?php

namespace Nuntius;

abstract class NuntiusPluginAbstract implements NuntiusPluginInterface {

  public $formats;

  /**
   * @return mixed
   */
  public function getFormats() {
    return $this->formats;
  }

  /**
   * @param mixed $formats
   *
   * @return NuntiusPluginInterface
   */
  public function setFormats($formats) {
    $this->formats = $formats;

    return $this;
  }

  protected function addEntry() {

  }

}
