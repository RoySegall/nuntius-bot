<?php

namespace Nuntius;

use FacebookMessengerSendApi\SendAPITransformInterface;

/**
 * Interface for post back.
 */
interface FacebookPostBackInterface {

  /**
   * Set the FB request data.
   *
   * @param array $fb_request
   *   The FB request object.
   *
   * @return TaskBaseInterface
   */
  public function setFbRequest(array $fb_request);

  /**
   * @return string|SendAPITransformInterface
   */
  public function postBack();

}
