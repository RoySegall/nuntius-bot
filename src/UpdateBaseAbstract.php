<?php

namespace Nuntius;
use Nuntius\Db\DbDispatcher;

/**
 * Interface UpdateBaseInterface.
 */
abstract class UpdateBaseAbstract implements UpdateBaseInterface {

  /**
   * The DB dispatcher service.
   *
   * @var DbDispatcher
   */
  protected $db;

  /**
   * The update ID.
   *
   * @var string
   */
  protected $updateId;

  /**
   * The entity manager.
   *
   * @var EntityManager
   */
  protected $entityManager;

  /**
   * UpdateBaseAbstract constructor.
   *
   * @param DbDispatcher $db
   *   The DB dispatcher.
   * @param $update_id
   *   The update ID.
   * @param EntityManager $entity_manager
   *   The entity storage.
   */
  function __construct(DbDispatcher $db, $update_id, EntityManager $entity_manager) {
    $this->db = $db;
    $this->updateId = $update_id;
    $this->entityManager = $entity_manager;
  }

}
