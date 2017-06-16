<?php

namespace Nuntius\FacebookSendApi;

/**
 * Class QuickReply
 */
class QuickReply extends SendAPITransform {

  /**
   * The content type.
   *
   * @param $content_type
   *   The content type.
   *
   * @return $this
   */
  public function contentType($content_type) {
    $this->data['content_type'] = $content_type;

    return $this;
  }

  /**
   * Set the title.
   *
   * @param $title
   *   The title.
   *
   * @return $this
   */
  public function title($title) {
    $this->data['title'] = $title;

    return $this;
  }

  /**
   * Set the payload.
   *
   * @param $payload
   *   The payload.
   *
   * @return $this
   */
  public function payload($payload) {
    $this->data['payload'] = $payload;

    return $this;
  }

  /**
   * Set the image URL.
   *
   * @param $image_url
   *   The image URL.
   *
   * @return $this
   */
  public function imageUrl($image_url) {
    $this->data['image_url'] = $image_url;

    return $this;
  }

}
