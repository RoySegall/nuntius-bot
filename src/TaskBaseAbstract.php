<?php

namespace Nuntius;
use Slack\RealTimeClient;

/**
 * Abstract class for the tasks plugins.
 */
abstract class TaskBaseAbstract implements TaskBaseInterface {

  /**
   * The RethinkDB connection.
   *
   * @var \Nuntius\NuntiusRethinkdb
   */
  protected $db;

  /**
   * The task ID.
   *
   * @var string
   */
  protected $taskId;

  /**
   * The entity manager.
   *
   * @var \Nuntius\EntityManager
   */
  protected $entityManager;

  /**
   * The client object.
   *
   * @var \Slack\RealTimeClient
   */
  protected $client;

  /**
   * The string of the last action.
   *
   * @var string
   */
  protected $data;

  /**
   * Constructor.
   *
   * @param \Nuntius\NuntiusRethinkdb $db
   *   The RethinkDB connection.
   * @param string $task_id
   *   The task ID.
   * @param \Nuntius\EntityManager $entity_manager
   *   The entity manager.
   */
  function __construct(NuntiusRethinkdb $db, $task_id, EntityManager $entity_manager) {
    $this->db = $db;
    $this->taskId = $task_id;
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function setClient(RealTimeClient $client) {
    $this->client = $client;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setData(array $data) {
    $this->data = $data;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function actOnPresenceChange() {
    // Do nothing by default.
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskId() {
    return $this->taskId;
  }

}
