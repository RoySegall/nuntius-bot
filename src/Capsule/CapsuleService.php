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
        $path = str_replace($this->getRoot() . '/', '', $path);
        $capsules[$folder_name] = ['path' => $path, 'machine_name' => $folder_name] + Yaml::parse(file_get_contents($yml_path));
      }
    }

    return $capsules;
  }

  /**
   * {@inheritdoc}
   */
  public function enableCapsule($capsule_name) {
    // Check if the console exists in the system table.
    $results = $this->getCapsuleRecord($capsule_name);

    // Check if the console already enabled.
    if (!$results) {
      $capsules = $this->getCapsules();

      if (empty($capsules[$capsule_name])) {
        throw new CapsuleErrorException("The capsule {$capsule_name} does not exists.");
      }

      $capsule = $capsules[$capsule_name];

      // Enable dependencies.
      if (!empty($capsule['dependencies'])) {

        foreach ($capsule['dependencies'] as $dependency) {

          if ($this->capsuleEnabled($dependency)) {
            continue;
          }

          $this->enableCapsule($dependency);
        }

        // Don't store the dependencies in the DB.
        unset($capsule['dependencies']);
      }

      // Add weight to the modules so we could load them by order the weight.
      $capsule['weight'] = $this->getHighestWeight() + 1;

      // Remove the dependencies from the capsule.
      $capsule['status'] = TRUE;

      $this->dbDispatcher->getStorage()->table('system')->save($capsule);
      return TRUE;
    }

    $results = reset($results);

    if ($results['status']) {
      // Already enabled.
      throw new CapsuleErrorException("The capsule {$capsule_name} is already enabled.");
    }

    // Enabled the capsule.
    $results['status'] = TRUE;
    $this->dbDispatcher->getStorage()->table('system')->update($results);
    return TRUE;
  }

  /**
   * Get the capsule record.
   *
   * @param string $capsule_name
   *  The capsule record in the DB.
   *
   * @return array
   */
  protected function getCapsuleRecord($capsule_name) {
    return $this
      ->dbDispatcher
      ->getQuery()
      ->table('system')
      ->condition('machine_name', $capsule_name)
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function disableCapsule($capsule_name) {
    if (!$records = $this->getCapsuleRecord($capsule_name)) {
      throw new CapsuleErrorException("The capsule {$capsule_name} does not exists.");
    }

    $record = reset($records);

    if (!$record['status']) {
      throw new CapsuleErrorException("The capsule {$capsule_name} is already disabled");
    }

    $record['status'] = FALSE;
    $this->dbDispatcher->getStorage()->table('system')->update($record);
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function capsuleList($mode) {
    if (!in_array($mode, self::CAPSULE_MODE)) {
      throw new CapsuleErrorException('enabled or disabled are the only allowed values');
    }

    $capsules = $this->dbDispatcher->getQuery()
      ->table('system')
      ->condition('status', $mode == 'enabled')
      ->execute();

    return array_map(function($item) {
      return $item['machine_name'];
    }, $capsules);

  }

  /**
   * {@inheritdoc}
   */
  public function getCapsuleImplementations($capsule_name, $implementation_type = NULL) {
    // Check if there's a capsule in that name.
    if (!$this->capsuleExists($capsule_name)) {
      throw new CapsuleErrorException("The capsule {$capsule_name} does not exists.");
    }

    // Check if the capsule enabled.
    if (!$this->capsuleEnabled($capsule_name)) {
      throw new CapsuleErrorException("The capsule {$capsule_name} is not enabled.");
    }

    // Check first that the capsule declared services.
    $capsule_records = $this->getCapsuleRecord($capsule_name);
    $capsule_record = reset($capsule_records);
    $services_file_path = $this->getRoot() . "/{$capsule_record['path']}/{$capsule_name}.services.yml";

    if (!file_exists($services_file_path)) {
      throw new CapsuleErrorException("The capsule {$capsule_name} does not declare any services.");
    }

    // Return the implementation list.
    $content = Yaml::parse(file_get_contents($services_file_path));

    if (!$implementation_type) {
      return $content;
    }

    if (empty($content[$implementation_type])) {
      return [];
    }

    return $content[$implementation_type];
  }

  /**
   * {@inheritdoc}
   */
  public function capsuleExists($capsule_name) {
    // Check we have the capsule in the file system.
    return in_array($capsule_name, array_keys($this->getCapsules()));
  }

  /**
   * {@inheritdoc}
   */
  public function capsuleEnabled($capsule_name) {
    return in_array($capsule_name, $this->capsuleList('enabled'));
  }

  /**
   * {@inheritdoc}
   */
  public function getHighestWeight() {
    $capsules = $this->getCapsulesForBootstrapping();
    $max_number = 0;

    foreach ($capsules as $capsule) {

      if (empty($capsule['weight'])) {
        continue;
      }

      if ($capsule['weight'] > $max_number) {
        return $capsule['weight'];
      }
    }

    return $max_number;
  }

  /**
   * {@inheritdoc}
   */
  public function getCapsulesForBootstrapping() {
    return $this->dbDispatcher
      ->getQuery()
      ->table('system')
      ->orderBy('weight', 'ASC')
      ->execute();
  }

}
