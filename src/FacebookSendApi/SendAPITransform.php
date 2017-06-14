<?php

namespace Nuntius\FacebookSendApi;

abstract class SendAPITransform implements SendAPITransformInterface {

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
