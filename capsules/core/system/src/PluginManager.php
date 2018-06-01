<?php

namespace Nuntius\System;

use Doctrine\Common\Annotations\AnnotationReader;
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
    $list = [];
    $active_capsules = $this->capsuleService->capsuleList('enabled');

    $name_space_dir = str_replace('\\', '/', $name_space);
    $files = $this->finder->files()->filter(function(\SplFileInfo $file) use($name_space_dir) {
      return strpos($file->getRealPath(), $name_space_dir) !== FALSE;
    });

    foreach ($files as $file) {
      $list[] = $file->getFileInfo()->getRealPath();
    }

    // Unique the list.
    $files = array_unique($list);
    $remove = [
      $this->capsuleService->getRoot() . '/',
      'src/',
      'capsules/tests/',
      'capsules/contrib/',
      'capsules/core/',
      'capsules/custom/',
    ];

    // Run over the files and get parse the plugins.
    foreach ($files as $file) {
      $clean_path = str_replace($remove, '', $file);
      $parts = explode('/', $clean_path);

      if (!in_array($parts[0], $active_capsules)) {
        continue;
      }

      $parts[0] = $this->machineNameToNameSpace($parts[0]);
      $plugin_namespace = str_replace('.php', '', '\Nuntius\\' . implode('\\', $parts));
      $this->processPlugin($plugin_namespace);
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
    $annotationReader = new AnnotationReader();
    $reflectionClass = new \ReflectionClass($data);
    $classAnnotations = $annotationReader->getClassAnnotations($reflectionClass);


    return ['id' => time() . microtime()];
  }

}
