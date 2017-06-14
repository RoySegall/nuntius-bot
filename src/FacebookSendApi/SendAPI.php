<?php

namespace Nuntius\FacebookSendApi;

use Nuntius\FacebookSendApi\ContentType\FacebookContentType;

class SendAPI {

  public $contentType;

  public function __construct() {
    $this->contentType = new FacebookContentType();
  }

}
