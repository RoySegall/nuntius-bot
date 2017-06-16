<?php

namespace Nuntius\FacebookSendApi;

use Nuntius\FacebookSendApi\Buttons\FacebookButtons;
use Nuntius\FacebookSendApi\ContentType\FacebookContentType;
use Nuntius\FacebookSendApi\Templates\FacebookTemplates;

class SendAPI {

  /**
   * @var FacebookContentType
   */
  public $contentType;

  /**
   * @var FacebookButtons
   */
  public $buttons;

  /**
   * @var FacebookTemplates
   */
  public $templates;

  /**
   * @var QuickReplies
   */
  public $quickReplies;

  /**
   * @var QuickReply
   */
  public $quickReply;

  /**
   * SendAPI constructor.
   */
  public function __construct() {
    $this->contentType = new FacebookContentType();
    $this->buttons = new FacebookButtons();
    $this->templates = new FacebookTemplates();
    $this->quickReplies = new QuickReplies();
    $this->quickReply = new QuickReply();
  }

}
