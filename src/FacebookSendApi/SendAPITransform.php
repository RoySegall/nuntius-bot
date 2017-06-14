<?php

namespace Nuntius\FacebookSendApi;

abstract class SendAPITransform {

  /**
   * @var array
   */
  protected $data;

  /**
   * @return array
   */
  public function getData() {
    return $this->data;
  }

}
