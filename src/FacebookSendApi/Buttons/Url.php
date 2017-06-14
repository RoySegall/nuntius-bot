<?php

namespace Nuntius\FacebookSendApi\Buttons;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class Url.
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/url-button
 */
class Url extends SendAPITransform {

  /**
   * Url constructor.
   */
  public function __construct() {
    $this->data['type'] = 'web_url';
    $this->data['webview_height_ratio'] = 'full';
  }

  /**
   * Set the URl.
   *
   * @param string $url
   *   The URl address.
   *
   * @return $this
   */
  public function url($url) {
    $this->data['url'] = $url;

    return $this;
  }

  /**
   * Set the title.
   *
   * @param $title
   *   The title of the URl.
   *
   * @return $this
   */
  public function title($title) {
    $this->data['title'] = $title;

    return $this;
  }

  /**
   * Set the webview height ratio.
   *
   * @param $webview_height_ratio
   *   The webview ratio. Valid values: compact, tall, full.
   *
   * @return $this
   */
  public function webviewHeightRatio($webview_height_ratio) {
    $this->data['webview_height_ratio'] = $webview_height_ratio;

    return $this;
  }

  /**
   * Set the messenger extensions.
   *
   * @param bool $extensions
   *   The extensions.
   *
   * @return $this
   */
  public function messengerExtensions($extensions) {
    $this->data['messenger_extensions'] = $extensions;

    return $this;
  }

  /**
   * Set the callback URl.
   *
   * @param $callback_url
   *   The callback URl.
   *
   * @return $this
   */
  public function callbackUrl($callback_url) {
    $this->data['callback_url'] = $callback_url;

    return $this;
  }

  /**
   * Set the webview share button.
   *
   * @param string $share_button
   *
   * @return $this.
   */
  public function webviewShareButton($share_button) {
    $this->data['webview_share_button'] = $share_button;

    return $this;
  }

}
