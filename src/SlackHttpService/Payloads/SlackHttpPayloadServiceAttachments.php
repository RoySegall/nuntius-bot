<?php

namespace SlackHttpService\Payloads;

/**
 * Post message attachment values.
 */
class SlackHttpPayloadServiceAttachments extends SlackHttpPayloadServiceAbstract {

  /**
   * {@inheritdoc}
   */
  protected function setDefaults() {
    $this->payload = [
      'fallback' => '',
      'color' => '',
      'pretext' => '',
      'author_name' => '',
      'author_icon' => '',
      'title' => '',
      'title_link' => '',
      'text' => '',
      'fields' => [],
      'image_url' => '',
      'thumb_url' => '',
      'footer' => '',
      'footer_icon' => '',
      'ts' => '',
    ];
  }

  /**
   * Set the fallback.
   *
   * @param $fallback
   *   The fallback.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setFallback($fallback) {
    $this->payload['fallback'] = $fallback;

    return $this;
  }

  /**
   * Set the color.
   *
   * @param $color
   *   The color.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setColor($color) {
    $this->payload['color'] = $color;

    return $this;
  }

  /**
   * Set the pretext.
   *
   * @param $pretext
   *   The pretext.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setPretext($pretext) {
    $this->payload['pretext'] = $pretext;

    return $this;
  }

  /**
   * Set the author name.
   *
   * @param $author_name
   *   The author name.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setAuthorName($author_name) {
    $this->payload['author_name'] = $author_name;

    return $this;
  }

  /**
   * Set the author icon.
   *
   * @param $author_icon
   *   The author icon.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setAuthorIcon($author_icon) {
    $this->payload['author_icon'] = $author_icon;

    return $this;
  }

  /**
   * Set the title.
   *
   * @param $title
   *   The title.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setTitle($title) {
    $this->payload['title'] = $title;

    return $this;
  }

  /**
   * Set the title link.
   *
   * @param $title_link
   *   The title link.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setTitleLink($title_link) {
    $this->payload['title_link'] = $title_link;

    return $this;
  }

  /**
   * Set the text.
   *
   * @param $text
   *   The text.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setText($text) {
    $this->payload['text'] = $text;

    return $this;
  }

  /**
   * Set the fields.
   *
   * @param array $fields
   *   The fields.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setFields(array $fields) {
    $this->payload['fields'] = $fields;

    return $this;
  }

  /**
   * Set the image url.
   *
   * @param $image_url
   *   The image url.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setImageUrl($image_url) {
    $this->payload['image_url'] = $image_url;

    return $this;
  }

  /**
   * Set the thumb url.
   *
   * @param $thumb_url
   *   The thumb url.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setThumbUrl($thumb_url) {
    $this->payload['thumb_url'] = $thumb_url;

    return $this;
  }

  /**
   * Set the footer.
   *
   * @param $footer
   *   The footer.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setFooter($footer) {
    $this->payload['footer'] = $footer;

    return $this;
  }

  /**
   * Set the ts.
   *
   * @param $ts
   *   The ts.
   *
   * @return SlackHttpPayloadServiceAttachments
   */
  public function setTs($ts) {
    $this->payload['ts'] = $ts;

    return $this;
  }

}
