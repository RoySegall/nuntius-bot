<?php

namespace Nuntius\Tasks;

use Nuntius\Nuntius;
use Nuntius\TaskConversationAbstract;
use Nuntius\TaskConversationInterface;

/**
 * Delete un-temporary context of question thus give the option to restart
 * again.
 */
class RestartQuestion extends TaskConversationAbstract implements TaskConversationInterface {

  /**
   * {@inheritdoc}
   */
  public function scope() {
    return [
      '/delete information/' => [
        'human_command' => 'delete information',
        'description' => 'Delete an information',
        'constraint' => '\Nuntius\TasksConstraint\RestartQuestionConstraint',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function conversationScope() {
    return 'temp';
  }

  /**
   * Get the user first name.
   */
  public function questionGetTaskId() {
    $tasks = Nuntius::getTasksManager()->getRestartableTasks();

    $labels = [];
    foreach ($tasks as $task) {
      $labels[] = '`' . $task['label'] . '`';
    }

    // Get all the un-temp context question.
    return "So... You want to delete information of a question. For which question?\n" . implode(',', $labels);
  }

  /**
   * Check if we need to restart the questions.
   */
  public function questionStartingAgain() {
    return 'Do you want to start the process again or should I restart the question?';
  }

  /**
   * {@inheritdoc}
   */
  public function collectAllAnswers() {
    // Delete the context of that question.
    $text = 'I deleted for you the information.';

    // Get the task ID.
    $tasks = Nuntius::getTasksManager()->getRestartableTasks();

    $task_id = '';
    foreach ($tasks as $task) {
      if ($task['label'] == $this->answers['GetTaskId']) {

        $task_id = $task['id'];
        $results = $this->db
          ->getTable('context')
          ->filter(\r\row('task')->eq($task['id']))
          ->filter(\r\row('user')->eq($this->data['user']))
          ->run(Nuntius::getRethinkDB()->getConnection());

        foreach ($results as $result) {
          Nuntius::getEntityManager()->get('context')->delete($result->getArrayCopy()['id']);
        }
      }
    }

    if (in_array($this->answers['StartingAgain'], ['yes', 'y'])) {
      $text .= " Let's start again.";

      /** @var TaskConversationInterface $task */
      $task = Nuntius::getTasksManager()->get($task_id);
      $text .= "\n" . $task->startTalking();
    }

    return $text;
  }

}
