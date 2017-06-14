<?php

namespace Nuntius\FacebookSendApi\ContentType;

class FacebookContentType {

  /**
   * @var Text
   */
  public $text;

  /**
   * @var Audio
   */
  public $audio;

  /**
   * @var File
   */
  public $file;

  /**
   * @var Image
   */
  public $image;

  /**
   * @var Video
   */
  public $video;

  /**
   * FacebookContentType constructor.
   *
   * Setting up the 
   */
  public function __construct() {
    $this->text = new Text();
    $this->audio = new Audio();
    $this->file = new File();
    $this->image = new Image();
    $this->video = new Video();
  }

}
