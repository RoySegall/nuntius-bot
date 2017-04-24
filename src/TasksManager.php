<?php

namespace Nuntius;

/**
 * Get te correct task which matches to the user input.
 */
class TasksManager {

  use NuntiusServicesTrait;

  /**
   * The DB service.
   *
   * @var NuntiusRethinkdb
   */
  protected $db;

  /**
   * The entity manager service.
   *
   * @var EntityManager
   */
  protected $entityManager;

  /**
   * List of tasks.
   *
   * @var TaskBaseInterface[]
   */
  protected $tasks;

  /**
   * Constructing the tasks manager.
   *
   * @param NuntiusRethinkdb $db
   *   The DB service.
   * @param EntityManager $entity_manager
   *   The entity manager service.
   * @param NuntiusConfig $config
   *   The config service.
   */
  function __construct(NuntiusRethinkdb $db, EntityManager $entity_manager, NuntiusConfig $config) {
    $this->db = $db;
    $this->entityManager = $entity_manager;

    $this->setTasks($config->getSetting('tasks'));
  }

  /**
   * Set the tasks.
   *
   * @param array $tasks
   *   List of tasks form.
   *
   * @return $this
   *   The current instance.
   */
  public function setTasks($tasks) {
    foreach ($tasks as $task => $namespace) {
      $this->tasks[$task] = new $namespace($this->db, $task, $this->entityManager);
    }

    return $this;
  }

  /**
   * Get all the tasks.
   *
   * @return TaskBaseInterface[]
   *   All the teaks.
   */
  public function getTasks() {
    return $this->tasks;
  }

  /**
   * Return the task object.
   *
   * @param $task
   *   The task ID.
   *
   * @return TaskBaseInterface
   *   Task.
   */
  public function get($task) {
    return $this->tasks[$task];
  }

  /**
   * Trigger the matching task to the given text.
   *
   * @param $text
   *   The text the user sent.
   *
   * @return bool|array
   *   If found return an array with the task object, callback and arguments.
   *   If not, return bool.
   */
  public function getMatchingTask($text) {
    foreach ($this->tasks as $task) {
      $scopes = $task->scope();

      foreach ($scopes as $sentence => $scope) {

        if (!$arguments = $this->matchTemplate($text, $sentence)) {
          continue;
        }

        if ($arguments === TRUE) {
         $arguments = [];
        }

        if ($task instanceof TaskConversationInterface) {
          return [$task, '', $arguments];
        }

        return [$task, $scope['callback'], $arguments];
      }
    }

    return FALSE;
  }

}
