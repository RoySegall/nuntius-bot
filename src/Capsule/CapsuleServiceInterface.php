<?php

namespace Nuntius\Capsule;

use Nuntius\Db\DbDispatcher;
use \Symfony\Component\Finder\Finder;

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
   *   The finder service.
   * @param DbDispatcher $dbDispatcher
   *   The DB dispatcher service.
   */
  public function __construct(Finder $finder, DbDispatcher $dbDispatcher);

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
   * Enabling a capsule.
   *
   * @param string $capsule_name
   *  The capsule name.
   *
   * @return bool
   * @throws CapsuleErrorException
   */
  public function enableCapsule($capsule_name);

  /**
   * Disable capsule.
   * 
   * @param string $capsule_name
   *  The capsule name.
   *
   * @return mixed
   */
  public function disableCapsule($capsule_name);

  /**
   * Get a list of the capsules.
   *
   * @param string $mode
   *  Two options are allowed:
   *    - 'enabled': Get all the enabled capsules.
   *    - 'disabled': Get all the disabled capsules.
   *
   * @return array
   *  List of capsules.
   */
  public function capsuleList($mode);

  /**
   * Get the implementations of the capsules from the services.yml file.
   *
   * @param string $capsule_name
   *  The capsule name.
   * @param string $implementation_type
   *  The implementation type in the services.yml file of the capsule.s
   *
   * @return mixed
   */
  public function getCapsuleImplementations($capsule_name, $implementation_type);

  /**
   * Checking if the capsule exists in the file system.
   *
   * @param string $capsule_name
   *  The name of capsule.
   *
   * @return mixed
   */
  public function capsuleExists($capsule_name);

  /**
   * Checking if the capsule enabled
   *
   * Unlike self::capsuleExists which check if the capsule exists in the file
   * system, this will check if the capsule enabled in the system.
   *
   * @param string $capsule_name
   *  The capsule name.
   *
   * @return mixed
   */
  public function capsuleEnabled($capsule_name);

}
