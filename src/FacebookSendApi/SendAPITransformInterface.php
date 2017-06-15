<?php

namespace Nuntius\FacebookSendApi;

interface SendAPITransformInterface {

  /**
   * @return array
   */
  public function getData();

  /**
   * Reset the element.
   *
   * @return $this
   */
  public function reset();

}
