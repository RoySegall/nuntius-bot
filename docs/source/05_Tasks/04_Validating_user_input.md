When the user is having a conversation with the bot, we need to validate the 
input. This is valid only for a conversation, and not a black box task, due to 
the fact that when the task is a black box task you only have a single input 
but, in a conversation there are multiple steps and we need to make sure the 
user can't go the next question until the current task input is valid.

### Adding a constraint to conversation
In order for a validating the question input we need to define the constraint on
the scope level. Let's look on the restarting tasks task which delete the 
context of a un-temporary task. The task is implemented at
`\Nuntius\Tasks\RestartQuestion`:

```php
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
```

The `constraint` key define the namespace of class. Validating method will be
invoke by a specific pattern. In order to validate the `questionStartingAgain`
method input we need to create a `validateStartingAgain`. If the method will
return `TRUE` then the input is OK. If the method will return a text the input
is bad and the text will be the text that explain why it failed. Let's have a
look on the code:
```php
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
```
