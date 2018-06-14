<?php

namespace Nuntius\System;

use Doctrine\Common\Annotations\AnnotationReader;
use Nuntius\Capsule\CapsuleServiceInterface;
use Nuntius\System\Annotations\NuntiusAnnotation;
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
   * @var bool
   */
  protected $requireEnabled = TRUE;

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
   * @param bool $requireEnabled
   *
   * @return PluginManager
   */
  public function setRequireEnabled(bool $requireEnabled) {
    $this->requireEnabled = $requireEnabled;

    return $this;
  }

  /**
   * @return bool
   */
  public function getRequireEnabled() {
    return $this->requireEnabled;
  }

  /**
   * Get all the plugins which a given relative namespace in a capsule.
   *
   * @param string $name_space
   *  The namespace.
   * @param NuntiusAnnotation $annotation
   *  The annotation handler which the plugins declaring.
   *
   * @return array[]
   *  List of the plugins namespaces.
   *
   * @throws \Doctrine\Common\Annotations\AnnotationException
   * @throws \Nuntius\Capsule\CapsuleErrorException
   * @throws \ReflectionException
   */
  public function getPlugins($name_space, NuntiusAnnotation $annotation) {
    $list = [];
    $active_capsules = $this->capsuleService->capsuleList('enabled');

    // Cloning the finder object so we won't override the capsule finder object.
    $finder = clone $this->finder;

    $name_space_dir = str_replace('\\', '/', $name_space);
    $files = $finder->files()->filter(function(\SplFileInfo $file) use($name_space_dir) {
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
    $plugins = [];

    foreach ($files as $file) {
      $clean_path = str_replace($remove, '', $file);
      $parts = explode('/', $clean_path);

      if ($this->requireEnabled && !in_array($parts[0], $active_capsules)) {
        continue;
      }

      $capsule = $parts[0];
      $parts[0] = $this->machineNameToNameSpace($parts[0]);

      $plugin_namespace = str_replace('.php', '', '\Nuntius\\' . implode('\\', $parts));
      $process = $this->processPlugin($plugin_namespace, get_class($annotation));

      $plugins[$process['id']] = $process + [
        'namespace' => $plugin_namespace,
        'provided_by' => $capsule,
      ];
    }

    return $plugins;
  }

  /**
   * Take the machine name of a capsule and convert it into a camel case one.
   *
   * @param string $machine_name
   *  The machine name of the plugin.
   *
   * @return string
   *  The namepsaced machine name.
   */
  protected function machineNameToNameSpace($machine_name) {
    return implode('', array_map(function($item) {
      return ucfirst($item);
    }, explode('_', $machine_name)));
  }

  /**
   * Read the plugin annotation and convert it into array.
   *
   * @param string $plugin_namespace
   *  The plugin namespace.
   * @param $annotation_id
   *  The annotation ID.
   *
   * @return array
   *
   * @throws \Doctrine\Common\Annotations\AnnotationException
   * @throws \ReflectionException
   */
  protected function processPlugin($plugin_namespace, $annotation_id) {
    $annotationReader = new AnnotationReader();
    $reflectionClass = new \ReflectionClass($plugin_namespace);
    return (array) $annotationReader->getClassAnnotation($reflectionClass, $annotation_id);
  }

}
