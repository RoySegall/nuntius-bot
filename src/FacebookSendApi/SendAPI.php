<?php

namespace Nuntius\FacebookSendApi;

use Nuntius\FacebookSendApi\Buttons\FacebookButtons;
use Nuntius\FacebookSendApi\ContentType\FacebookContentType;

class SendAPI {

  /**
   * @var FacebookContentType
   */
  public $contentType;

  /**
   * @var FacebookButtons
   */
  public $buttons;

  public function __construct() {
    $this->contentType = new FacebookContentType();
    $this->buttons = new FacebookButtons();
  }

}
