<?php

namespace Nuntius\System;

use Nuntius\Capsule\CapsuleServiceInterface;
use Symfony\Component\Finder\Finder;

class PluginManager {

  /**
   * @var Finder
   */
  protected $finder;

  /**
   * @var CapsuleServiceInterface
   */
  protected $capsuleService;

  /**
   * PluginManager constructor.
   * @param Finder $finder
   * @param CapsuleServiceInterface $capsule_service
   */
  public function __construct(Finder $finder, CapsuleServiceInterface $capsule_service) {
    $this->finder = $finder;
    $this->capsuleService = $capsule_service;
  }

  /**
   * Get all the plugins which a given relative namespace in a capsule.
   *
   * @param string $name_space
   *  The namespace.
   *
   * @return array[]
   *  List of the plugins namespaces.
   */
  public function getPlugins($name_space) {
    $capsules = $this->capsuleService->getCapsulesForBootstrapping();

    $list = [];

    foreach ($capsules as $capsule) {
      $files = $this->finder->files()->in($capsule['path'] . '/src/' . str_replace('\\', '/', $name_space));

      foreach ($files as $file) {
        $list[] = $file->getFileInfo()->getRealPath();
      }
    }

    return $list;
  }

}
