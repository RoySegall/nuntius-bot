<?php

namespace Nuntius\Capsule;

class CapsuleService implements CapsuleServiceInterface {

  /**
   * @var string
   *
   * The root folder of the project.
   */
  protected $root;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->root = getcwd();
  }

  /**
   * {@inheritdoc}
   */
  public function getCapsules() {
  }

  /**
   * {@inheritdoc}
   */
  public function getRoot() {
    return $this->root;
  }

  /**
   * {@inheritdoc}
   */
  public function setRoot($root) {
    $this->root = $root;

    return $this;
  }

}
