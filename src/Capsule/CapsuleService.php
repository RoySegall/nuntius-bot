<?php

namespace Nuntius\Capsule;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class CapsuleService implements CapsuleServiceInterface {

  /**
   * @var string
   *
   * The root folder of the project.
   */
  protected $root;

  /**
   * @var Finder
   *
   * Symfony finder service.
   */
  protected $finder;

  /**
   * {@inheritdoc}
   */
  public function __construct(Finder $finder) {
    $this->root = getcwd();
    $this->finder = $finder;
  }

  /**
   * {@inheritdoc}
   */
  public function getCapsules() {

    $folders = [];

    /** @var SplFileInfo[] $directories */
    $directories = $this->finder
      ->directories()
      ->in($this->getRoot() . '/capsules');

    foreach ($directories as $item) {
      // todo: check if there is a yml file.
      $folders[] = $item;
    }

    // todo: order by dependencies.

    return $folders;

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
