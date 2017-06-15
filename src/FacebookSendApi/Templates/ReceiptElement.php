<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class ReceiptElement
 */
class ReceiptElement extends SendAPITransform {

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
   * Set the quantity.
   *
   * @param $quantity
   *   The quantity.
   *
   * @return $this
   */
  public function quantity($quantity) {
    $this->data['quantity'] = $quantity;

    return $this;
  }

  /**
   * Set the price.
   *
   * @param $price
   *   The price.
   *
   * @return $this
   */
  public function price($price) {
    $this->data['price'] = $price;

    return $this;
  }

  /**
   * Set the currency.
   *
   * @param $currency
   *   The currency.
   *
   * @return $this
   */
  public function currency($currency) {
    $this->data['currency'] = $currency;

    return $this;
  }

}
