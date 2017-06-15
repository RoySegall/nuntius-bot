<?php

namespace Nuntius\FacebookSendApi;

abstract class SendAPITransform implements SendAPITransformInterface {

  /**
   * @var array
   */
  protected $data;

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this->data;
  }

  /**
   * {@inheritdoc}
   */
  public function reset() {
    $this->data = [];

    return $this;
  }

}
