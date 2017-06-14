<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\Buttons\Url;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class OpenGraph
 *
 * @see https://developers.facebook.com/docs/messenger-platform/open-graph-template
 */
class OpenGraph extends SendAPITransform {

  /**
   * OpenGraph constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'open_graph';
    $this->data['attachment']['payload']['elements'] = [];
  }

  /**
   * Setting the URL of the open graph.
   *
   * @param string $url
   *   The URL address.
   *
   * @return $this
   */
  public function url($url) {
    $this->data['attachment']['payload']['elements'][0]['url'] = $url;

    return $this;
  }

  /**
   * Add the button url.
   *
   * @param ButtonInterface $button
   *   The button object.
   *
   * @return $this
   */
  public function button(ButtonInterface $button) {
    $this->data['attachment']['payload']['elements'][0]['buttons'][] = $button->getData();

    return $this;
  }

}
