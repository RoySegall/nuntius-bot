<?php

namespace Nuntius\FacebookSendApi;

/**
 * Class QuickReplies
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/quick-replies
 */
class QuickReplies extends SendAPITransform {

  /**
   * Set the text.
   *
   * @param $text
   *   The text.
   *
   * @return $this
   */
  public function text($text) {
    $this->data['text'] = $text;

    return $this;
  }

  /**
   * Set the attachment.
   *
   * @param $attachment
   *   The attachment.
   *
   * @return $this
   */
  public function attachment($attachment) {
    $this->data['attachment'] = $attachment;

    return $this;
  }

  /**
   * Adding a quick reply object.
   *
   * @param QuickReply $quick_reply
   *   The quick reply object.
   *
   * @return $this
   */
  public function addQuickReply(QuickReply $quick_reply) {
    $this->data['quick_replies'][] = $quick_reply->getData();

    return $this;
  }

}
