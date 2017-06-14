<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class Generic
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/generic-template
 */
class Generic extends SendAPITransform {

  /**
   * Generic constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'generic';
  }

  /**
   * Set the text.
   *
   * @param $text
   *   The text of the button.
   *
   * @return $this
   */
  public function text($text) {
    $this->data['attachment']['payload']['title'] = $text;

    return $this;
  }

  /**
   * Set shareable.
   *
   * @param $shareable
   *   Set to false to disable the native share button in Messenger for the
   *   template message.
   *
   * @return $this
   */
  public function sharable($shareable) {
    $this->data['attachment']['payload']['sharable'] = $shareable;

    return $this;
  }

  /**
   * Set image aspect ratio.
   *
   * @param $image_aspect_ratio
   *   Aspect ratio used to render images specified by image_url in element
   *   objects. Must be horizontal or square. Defaults to horizontal.
   *
   * @return $this
   */
  public function imageAspectRatio($image_aspect_ratio) {
    $this->data['attachment']['payload']['image_aspect_ratio'] = $image_aspect_ratio;

    return $this;
  }

  /**
   * Set the element.
   *
   * @param Element $element
   *   The element object.
   *
   * @return $this
   */
  public function addElement(Element $element) {
    $this->data['attachment']['payload']['elements'][] = $element->getData();

    return $this;
  }

}
