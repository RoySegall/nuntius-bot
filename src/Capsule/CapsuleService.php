<?php

namespace Nuntius\Capsule;

use Nuntius\Db\DbDispatcher;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

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
   * @var DbDispatcher
   *
   * The DB dispatcher service.
   */
  protected $dbDispatcher;

  /**
   * {@inheritdoc}
   */
  public function __construct(Finder $finder, DbDispatcher $dbDispatcher) {
    $this->root = getcwd();
    $this->finder = $finder;
    $this->dbDispatcher = $dbDispatcher;
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

  /**
   * {@inheritdoc}
   */
  public function getCapsules() {
    $capsules = [];

    /** @var SplFileInfo[] $directories */
    $directories = $this->finder
      ->directories()
      ->in($this->getRoot() . '/capsules');

    foreach ($directories as $item) {
      $folder_name = $item->getFileInfo()->getBasename();
      $path = $item->getPath() . '/' . $folder_name;
      $yml_path = $path . '/' . $folder_name . '.capsule.yml';

      if (file_exists($yml_path)) {
        $capsules[$folder_name] = ['path' => $path] + Yaml::parse(file_get_contents($yml_path));
      }
    }

    return $capsules;
  }

  /**
   * {@inheritdoc}
   */
  public function enableCapsule($capsule_name) {

  }

  /**
   * {@inheritdoc}
   */
  public function disableCapsule($capsule_name) {

  }

  /**
   * {@inheritdoc}
   */
  public function capsuleList($mode) {

  }

  /**
   * {@inheritdoc}
   */
  public function getCapsuleImplementations($capsule_name, $implementation_type) {

  }

  /**
   * {@inheritdoc}
   */
  public function capsuleExists($capsule_name) {

  }

  /**
   * {@inheritdoc}
   */
  public function capsuleEnabled($capsule_name) {

  }

}
