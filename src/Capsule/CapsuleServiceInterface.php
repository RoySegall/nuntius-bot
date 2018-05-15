<?php

namespace Nuntius\Capsule;

use \Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

/**
 * Interface CapsuleServiceInterface
 *
 * @package Nuntius\Capsule
 */
interface CapsuleServiceInterface {

  /**
   * CapsuleServiceInterface constructor.
   *
   * @param Finder $finder
   *   The finder object.
   */
  public function __construct(Finder $finder);

  /**
   * Search for all the capsules in the system.
   *
   * Capsules can be located in three folders:
   *  - core: All the capsules which provided by nuntius.
   *  - contrib: All the capsules which downloaded by composer.
   *  - custom: Capsules which written by the user.
   *
   * @return mixed
   */
  public function getCapsules();

  /**
   * Get the root folder.
   *
   * @return string
   */
  public function getRoot();

  /**
   * Set the root property.
   *
   * @param string $root
   *  The root folder of the projects.
   *
   * @return CapsuleServiceInterface
   *  The current instance.
   */
  public function setRoot($root);

  /**
   * @param $capsule_name
   * @param $skip_dependencies
   *
   * @return mixed
   */
  public function enableCapsule($capsule_name, $skip_dependencies);

  /**
   * @param $capsule_name
   *
   * @return mixed
   */
  public function disableCapsule($capsule_name);

  /**
   * @param $mode
   *
   * @return mixed
   */
  public function capsuleList($mode);

  /**
   * @param $capsule_name
   *
   * @return mixed
   */
  public function getCapsuleImplementations($capsule_name);

  /**
   * @param $capsule_name
   *
   * @return mixed
   */
  public function capsuleExists($capsule_name);

  /**
   * @param $capsule_name
   *
   * @return mixed
   */
  public function capsuleEnabled($capsule_name);

}
