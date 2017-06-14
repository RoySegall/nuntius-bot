<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\Buttons\Url;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class Element
 */
class Element extends SendAPITransform {

  /**
   * Set the title.
   *
   * @param string $title
   *   The title.
   *
   * @return $this
   */
  public function title($title) {
    $this->data['title'] = $title;

    return $this;
  }

  /**
   * Set subtitle.
   *
   * @param $subtitle
   *   The subtitle.
   *
   * @return $this
   */
  public function subtitle($subtitle) {
    $this->data['subtitle'] = $subtitle;

    return $this;
  }

  /**
   * Set the image URl.
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

  /**
   * Set the default action.
   *
   * @param Url $default_action
   *   The default action.
   *
   * @return $this
   */
  public function defaultAction(Url $default_action) {
    $button = $default_action->getData();

    unset($button['title']);
    $this->data['default_action'] = $button;

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

    $this->data['buttons'][] = $button->getData();

    return $this;
  }

  /**
   * When using the same object for a list we need to reset the buttons.
   *
   * @return $this
   */
  public function resetButtons() {
    $this->data['buttons'] = [];

    return $this;
  }

}
