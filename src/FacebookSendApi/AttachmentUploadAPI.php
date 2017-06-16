<?php

namespace Nuntius\FacebookSendApi;

/**
 * Class QuickReplies
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/attachment-upload
 */
class AttachmentUploadAPI extends SendAPITransform {

  /**
   * Set the type.
   *
   * @param $type
   *   The type.
   *
   * @return $this
   */
  public function type($type) {
    $this->data['attachment']['type'] = $type;

    return $this;
  }

  /**
   * Set the URL of the upload.
   *
   * @param $url
   *   The URl of the asset.
   *
   * @return $this
   */
  public function url($url) {
    $this->data['attachment']['payload']['url'] = $url;

    return $this;
  }

  /**
   * Set if the assert is is reusable.
   *
   * @param $is_reusable
   *   Set if is reusable.
   *
   * @return $this
   */
  public function isReusable($is_reusable) {
    $this->data['attachment']['payload']['is_reusable'] = $is_reusable;

    return $this;
  }

}
