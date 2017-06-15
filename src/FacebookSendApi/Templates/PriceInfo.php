<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class PriceInfo
 */
class PriceInfo extends SendAPITransform {

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
   * Set the amount.
   *
   * @param $amount
   *   The amount.
   *
   * @return $this
   */
  public function amount($amount) {
    $this->data['amount'] = $amount;

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
