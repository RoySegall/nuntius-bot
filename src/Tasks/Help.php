<?php

namespace Nuntius\Tasks;

use Nuntius\Nuntius;
use Nuntius\TaskBaseAbstract;
use Nuntius\TaskBaseInterface;

/**
 * Remind to the user something to do.
 */
class Help extends TaskBaseAbstract implements TaskBaseInterface {

  /**
   * {@inheritdoc}
   */
  public function scope() {
    return [
      '/help/' => [
        'human_command' => 'help',
        'description' => 'Giving you help',
        'callback' => 'listOfScopes',
      ],
    ];
  }

  /**
   * Get all the tasks and their scope(except for this one).
   */
  public function listOfScopes() {
    $task_manager = Nuntius::getTasksManager();

    $text = [];

    foreach ($task_manager->getTasks() as $task_id => $task) {
      if ($task_id == 'help') {
        continue;
      }

      foreach ($task->scope() as $scope) {
        $text[] = '`' . $scope['human_command'] . '`: ' . $scope['description'];
      }
    }

    return implode("\n", $text);
  }

}
