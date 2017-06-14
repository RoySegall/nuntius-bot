<?php

namespace Nuntius\FacebookSendApi\Buttons;

class FacebookButtons {

  /**
   * @var Url
   */
  public $url;

  /**
   * @var postBack
   */
  public $postBack;

  /**
   * @var Call
   */
  public $call;

  /**
   * @var Share
   */
  public $share;

  /**
   * @var Buy
   */
  public $buy;

  /**
   * @var LogIn
   */
  public $logIn;

  /**
   * @var LogOut
   */
  public $logOut;

  /**
   * FacebookButtons constructor.
   */
  public function __construct() {
    $this->url = new Url();
    $this->postBack = new postBack();
    $this->call = new Call();
    $this->share = new Share();
    $this->buy = new Buy();
    $this->logIn = new LogIn();
    $this->logOut = new LogOut();
  }

}
