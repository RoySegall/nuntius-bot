<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class ListTemplate
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/list-template
 */
class ListTemplate extends SendAPITransform {

  /**
   * ListTemplate constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'list';
  }

  /**
   * Set the top element style.
   *
   * @param $top_element_style
   *   Value must be large or compact. Default to large if not specified.
   *
   * @return $this
   */
  public function topElementStyle($top_element_style) {
    $this->data['attachment']['payload']['top_element_style'] = $top_element_style;

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

  /**
   * Add a button.
   *
   * @param ButtonInterface $button
   *   The button object.
   *
   * @return $this
   * @throws \Exception
   */
  public function addButton(ButtonInterface $button) {

    if (!empty($this->data['buttons'])) {

      if (count($this->data['buttons']) >= 3) {
        throw new \Exception('You cannot send more than 3 buttons.');
      }
    }

    $this->data['attachment']['payload']['buttons'][] = $button->getData();

    return $this;
  }

}
