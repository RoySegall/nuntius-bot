<?php

namespace Nuntius;

use GuzzleHttp\Promise\Promise;
use r\Queries\Tables\Table;

/**
 * Abstract class for the tasks conversation plugins.
 */
abstract class TaskConversationAbstract extends TaskBaseAbstract implements TaskConversationInterface {

  /**
   * List of answers.
   *
   * @var array
   */
  protected $answers;

  /**
   * {@inheritdoc}
   */
  public function startTalking() {
    $methods = get_class_methods($this);

    // Setting up the context.
    $context = [
      'user' => $this->data['user'],
      'task' => $this->taskId,
    ];

    // Check if we have a running context for the user.
    if (!$this->checkForContext($this->db->getTable('running_context'))) {
      // Create a running context.
      $this->entityManager->get('running_context')->insert($context);
    }

    // Check if we have a context in the DB.
    if (!$db_context = $this->checkForContext($this->db->getTable('context'))) {
      // Get the questions methods.
      foreach ($methods as $method) {
        if (strpos($method, 'question') === 0) {
          $context['questions'][$method] = FALSE;
        }
      }

      // Insert it into the DB.
      $this->entityManager->get('context')->insert($context);
    }
    else {
      $context = reset($db_context)->getArrayCopy();

      // Converting the answers to normal array.
      $context['questions'] = $context['questions']->getArrayCopy();
    }

    // Fire the question.
    foreach ($context['questions'] as $question => $answer) {
      if ($answer === FALSE) {
        return $this->{$question}();
      }
    }

    // All the questions have been answered. Trigger the end of the session.
    foreach ($context['questions'] as $key => $value) {
      $this->answers[str_replace('question', '', $key)] = $value;
    }

    // Delete the current running context.
    $this->deleteRunningContext();

    $text = $this->collectAllAnswers();

    if ($this->conversationScope() != 'forever') {
      unset($context['id']);
      $this->entityManager->get('context_archive')->insert($context);
      $this->deleteContext();
    }

    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function setAnswer($text) {
    // Prepare the context from the DB.
    $context = $this->checkForContext($this->db->getTable('context'));
    $context = reset($context);
    $context['questions'] = $context['questions']->getArrayCopy();

    $constraint = $this->getConstraint();

    // Look for the answer we need to handle.
    foreach ($context['questions'] as $question => $answer) {
      if ($answer !== FALSE) {
        continue;
      }

      if ($constraint) {
        $method = str_replace('question', 'validate', $question);

        if (method_exists($constraint, $method)) {
          if (($error = $constraint->{$method}($text)) !== TRUE) {
            return $error;
          }
        }
      }

      $context['questions'][$question] = $text;

      $this->entityManager->get('context')
        ->load($context['id'])
        ->update($context['id'], $context);
      break;
    }
  }

  /**
   * Checking the we have a context in the given table.
   *
   * @param Table $table
   *   The table object.
   *
   * @return array
   *   The array copy of the results.
   */
  protected function checkForContext(Table $table) {
    $results = $table
      ->filter(\r\row('user')->eq($this->data['user']))
      ->filter(\r\row('task')->eq($this->taskId))
      ->run($this->db->getConnection());

    return $results->toArray();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteRunningContext() {
    $running_context = $this->checkForContext($this->db->getTable('running_context'));
    $running_context = reset($running_context)->getArrayCopy();
    $this->entityManager->get('running_context')->delete($running_context['id']);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteContext() {
    $running_context = $this->checkForContext($this->db->getTable('context'));
    $running_context = reset($running_context)->getArrayCopy();
    $this->entityManager->get('context')->delete($running_context['id']);
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraint() {
    $scopes = $this->scope();
    $scope = reset($scopes);

    if (empty($scope['constraint'])) {
      return;
    }

    return new $scope['constraint'];
  }

  /**
   * Get the context of the current task for the current user or a given
   * username.
   *
   * @param $task_id
   *   The task ID.
   * @param null $user
   *   Optional. The user ID. In case of NULL the current user will be query.
   *
   * @return array
   *   The task information.
   */
  protected function getTaskContext($task_id, $user = NULL) {
    if (!$user) {
      $user = $this->data['user'];
    }

    $results = $this->db
      ->getTable('context')
      ->filter(\r\row('task')->eq($task_id))
      ->filter(\r\row('user')->eq($user))
      ->run(Nuntius::getRethinkDB()->getConnection());

    $rows = [];
    foreach ($results as $result) {
      $rows[] = $result->getArrayCopy();
    }

    return reset($rows);
  }

}
