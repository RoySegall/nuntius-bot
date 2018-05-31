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
    $processed_files = [];

    foreach ($capsules as $capsule) {
      $base_namespace = '\Nuntius\\' . $this->machineNameToNameSpace($capsule['machine_name']) . '\\' . $name_space;
      $name_space_dir = str_replace('\\', '/', $name_space);

      $files = $this->finder->files()->filter(function(\SplFileInfo $file) use($name_space_dir) {
        // For some reason we get files from the capsule root so we need to
        // filter those who are not in the path of the capsule.
        return strpos($file->getRealPath(), $name_space_dir) !== FALSE;
      });

      foreach ($files as $file) {
        $path = $file->getFileInfo()->getRealPath();

        if (in_array($path, $processed_files)) {
          continue;
        }

        $data = [
          'namespace' => $base_namespace . '\\' . str_replace('.php', '', $file->getFileInfo()->getFileName()),
          'path' => $path,
        ];

        $plugin = $this->processPlugin($data);

        $list[$plugin['id']] = $data + $plugin;

        $processed_files[] = $path;

        break;
      }
    }

    return $list;
  }

  protected function machineNameToNameSpace($machine_name) {
    return implode('', array_map(function($item) {
      return ucfirst($item);
    }, explode('_', $machine_name)));
  }

  protected function processPlugin($data) {
//    require_once $data['path'];
//
//    new \ReflectionClass($data['namespace']);

    return ['id' => time() . microtime()];
  }

}
