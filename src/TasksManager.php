<?php

namespace Nuntius;

/**
 * Get te correct task which matches to the user input.
 */
class TasksManager {

  use NuntiusServicesTrait;

  /**
   * List of tasks.
   *
   * @var TaskBaseInterface[]
   */
  protected $tasks;

  /**
   * Constructing the tasks manager.
   *
   * @param array $tasks
   *   List of all the tasks.
   */
  function __construct($tasks) {
    $db = Nuntius::getRethinkDB();
    $entity_manager = Nuntius::getEntityManager();

    foreach ($tasks as $task => $namespace) {
      $this->tasks[$task] = new $namespace($db, $task, $entity_manager);
    }
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
   * Get all the tasks.
   *
   * @return TaskBaseInterface[]
   *   All the teaks.
   */
  public function getTasks() {
    return $this->tasks;
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
