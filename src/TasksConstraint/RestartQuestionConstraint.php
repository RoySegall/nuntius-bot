<?php

namespace Nuntius\TasksConstraint;

use Nuntius\AbstractQuestionConstraint;
use Nuntius\Nuntius;

/**
 * Validating the restart question tasks.
 */
class RestartQuestionConstraint extends AbstractQuestionConstraint {

  /**
   * Validate the input of tasks.
   *
   * @param $value
   *   The input of the user.
   *
   * @return bool|string
   */
  public function validateGetTaskId($value) {
    $tasks = Nuntius::getTasksManager()->getRestartableTasks();
    foreach ($tasks as $task) {
      if ($task['label'] == $value) {
        return TRUE;
      }
    }
    return "Hmmm..... it's look like `{$value}` is not a task I know.";
  }

  /**
   * Validate the user input the correct text.
   *
   * @param string $value
   *   The input of the user.
   *
   * @return bool|string
   */
  public function validateStartingAgain($value) {
    if (!in_array($value, ['yes', 'no', 'y', 'n'])) {
      return 'The answer need to be one of the following: ' . implode(', ' , ['`yes`', '`no`', '`y`', '`n`']);
    }

    return TRUE;
  }

}
