<?php

namespace Nuntius\FacebookSendApi\Buttons;

use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class Buy.
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/buy-button
 */
class Buy extends SendAPITransform implements ButtonInterface {

  /**
   * Buy constructor.
   */
  public function __construct() {
    $this->data['type'] = 'payment';
  }

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
   * Set the payload.
   *
   * @param string $payload
   *   The payload.
   *
   * @return $this
   */
  public function payload($payload) {
    $this->data['payload'] = $payload;

    return $this;
  }

  /**
   * Set the currency.
   *
   * @param string $currency
   *   The currency.
   *
   * @return $this
   */
  public function currency($currency) {
    $this->data['payment_summary']['currency'] = $currency;

    return $this;
  }

  /**
   * Set the payment type.
   *
   * @param mixed $payment_type
   *   The currency. Must be FIXED_AMOUNT or FLEXIBLE_AMOUNT.
   *
   * @return $this
   */
  public function paymentType($payment_type) {
    $this->data['payment_summary']['payment_type'] = $payment_type;

    return $this;
  }

  /**
   * Set the payment type.
   *
   * @param string $merchant_name
   *   Name of merchant.
   *
   * @return $this
   */
  public function merchantName($merchant_name) {
    $this->data['payment_summary']['merchant_name'] = $merchant_name;

    return $this;
  }

  /**
   * Set the request user info.
   *
   * @param array $requested_user_info
   *   Information requested from person that will render in the dialog.
   *   Valid values: shipping_address, contact_name, contact_phone,
   *   contact_email. You can config these based on your product need.
   *
   * @return $this
   */
  public function requestedUserInfo(array $requested_user_info = []) {

    if (!$requested_user_info) {
      $requested_user_info = [
        'shipping_address',
        'contact_name',
        'contact_phone',
        'contact_email',
      ];
    }

    $this->data['payment_summary']['requested_user_info'] = $requested_user_info;

    return $this;
  }

  /**
   * Set the request user info.
   *
   * @param array $price_list
   *   List of objects used to calculate total price. Each label is rendered as
   *   a line item in the checkout dialog.
   *   Each item need to have a label and amount.
   *
   * @return $this
   */
  public function priceList(array $price_list = []) {

    $this->data['payment_summary']['price_list'] = $price_list;

    return $this;
  }

}
