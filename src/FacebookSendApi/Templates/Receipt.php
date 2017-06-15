<?php

namespace Nuntius\FacebookSendApi\Templates;

use Nuntius\FacebookSendApi\Buttons\ButtonInterface;
use Nuntius\FacebookSendApi\Buttons\Url;
use Nuntius\FacebookSendApi\SendAPITransform;

/**
 * Class Receipt
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/receipt-template
 */
class Receipt extends SendAPITransform {

  /**
   * Receipt constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'receipt';
  }

  /**
   * Set the sharable.
   *
   * @param $sharable
   *   If it sharable.
   *
   * @return $this
   */
  public function sharable($sharable) {
    $this->data['attachment']['payload']['sharable'] = $sharable;

    return $this;
  }

  /**
   * Set the recipient name.
   *
   * @param $recipient_name
   *   The recipient name.
   *
   * @return $this
   */
  public function recipientName($recipient_name) {
    $this->data['attachment']['payload']['recipient_name'] = $recipient_name;

    return $this;
  }

  /**
   * Set the merchant name.
   *
   * @param $merchant_name
   *   The merchant name.
   *
   * @return $this
   */
  public function merchantName($merchant_name) {
    $this->data['attachment']['payload']['merchant_name'] = $merchant_name;

    return $this;
  }

  /**
   * Set the order number.
   *
   * @param $order_number
   *   The order number.
   *
   * @return $this
   */
  public function orderNumber($order_number) {
    $this->data['attachment']['payload']['order_number'] = $order_number;

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
    $this->data['attachment']['payload']['currency'] = $currency;

    return $this;
  }

  /**
   * Set the payment method.
   *
   * @param $payment_method
   *   The payment method.
   *
   * @return $this
   */
  public function paymentMethod($payment_method) {
    $this->data['attachment']['payload']['payment_method'] = $payment_method;

    return $this;
  }

  /**
   * Set the time stamp.
   *
   * @param $timestamp
   *   The time stamp.
   *
   * @return $this
   */
  public function timestamp($timestamp) {
    $this->data['attachment']['payload']['timestamp'] = $timestamp;

    return $this;
  }

  /**
   * Set the order url.
   *
   * @param $order_url
   *   The order url.
   *
   * @return $this
   */
  public function orderUrl($order_url) {
    $this->data['attachment']['payload']['order_url'] = $order_url;

    return $this;
  }

  /**
   * Add element.
   *
   * @param ReceiptElement $element
   *   The receipt element object.
   *
   * @return $this
   */
  public function addElement(ReceiptElement $element) {
    $this->data['attachment']['payload']['elements'][] = $element->getData();

    return $this;
  }

  /**
   * Set address street 1.
   *
   * @param $street1
   *   Street 1 address.
   *
   * @return $this
   */
  public function street1($street1) {
    $this->data['attachment']['payload']['address']['street_1'] = $street1;

    return $this;
  }

  /**
   * Set address street 2.
   *
   * @param $street2
   *   Street 2 address.
   *
   * @return $this
   */
  public function street2($street2) {
    $this->data['attachment']['payload']['address']['street_2'] = $street2;

    return $this;
  }

  /**
   * Set address city.
   *
   * @param $city
   *   The city.
   *
   * @return $this
   */
  public function city($city) {
    $this->data['attachment']['payload']['address']['city'] = $city;

    return $this;
  }

  /**
   * Set address postal code.
   *
   * @param $postal_code
   *   The postal code.
   *
   * @return $this
   */
  public function postalCode($postal_code) {
    $this->data['attachment']['payload']['address']['postal_code'] = $postal_code;

    return $this;
  }

  /**
   * Set address state.
   *
   * @param $state
   *   The state.
   *
   * @return $this
   */
  public function state($state) {
    $this->data['attachment']['payload']['address']['state'] = $state;

    return $this;
  }

  /**
   * Set address country.
   *
   * @param $country
   *   The country.
   *
   * @return $this
   */
  public function country($country) {
    $this->data['attachment']['payload']['address']['country'] = $country;

    return $this;
  }

  /**
   * Set address subtotal.
   *
   * @param $subtotal
   *   The summary subtotal.
   *
   * @return $this
   */
  public function subtotal($subtotal) {
    $this->data['attachment']['payload']['summary']['subtotal'] = $subtotal;

    return $this;
  }

  /**
   * Set address shipping cost.
   *
   * @param $shipping_cost
   *   The summary shipping cost.
   *
   * @return $this
   */
  public function shippingCost($shipping_cost) {
    $this->data['attachment']['payload']['summary']['shipping_cost'] = $shipping_cost;

    return $this;
  }

  /**
   * Set address total tax.
   *
   * @param $total_tax
   *   The summary total tax cost.
   *
   * @return $this
   */
  public function totalTax($total_tax) {
    $this->data['attachment']['payload']['summary']['total_tax'] = $total_tax;

    return $this;
  }

  /**
   * Set address total tax.
   *
   * @param $total_tax
   *   The summary total tax cost.
   *
   * @return $this
   */
  public function totalCost($total_tax) {
    $this->data['attachment']['payload']['summary']['total_tax'] = $total_tax;

    return $this;
  }

  /**
   * Add adjustment.
   *
   * @param $name
   *   A title for the adjustment.
   * @param $amount
   *   The adjustment amount.
   *
   * @return $this
   */
  public function addAdjustment($name, $amount) {
    $this->data['attachment']['payload']['adjustment'][] = [
      'name' => $name,
      'amount' => $amount,
    ];

    return $this;
  }

}
