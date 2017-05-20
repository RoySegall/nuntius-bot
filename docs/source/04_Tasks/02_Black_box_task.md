```php
<?php

namespace Nuntius\Tasks;

/**
 * Remind to the user something to do.
 */
class Reminders extends TaskBaseAbstract implements TaskBaseInterface {

  /**
   * {@inheritdoc}
   */
  public function scope() {
    return [
      '/remind me (.*)/' => [
        'human_command' => 'remind me REMINDER',
        'description' => 'Next time you log in I will remind you what you '
        . ' wrote in the REMINDER',
        'callback' => 'addReminder',
      ],
    ];
  }

  /**
   * Adding a reminder to the DB.
   *
   * @param string $reminder
   *   The reminder of the user.
   *
   * @return string
   *   You got it dude!
   */
  public function addReminder($reminder) {
    $this->entityManager->get('reminders')->insert([
      'reminder' => $reminder,
      'user' => $this->data['user'],
    ]);

    return 'OK! I got you covered!';
  }

}
```
In the `method` scope we define to which text we need to respond. Each `(.*)` is
an argument. The keys meaning are:
  * `human_command`: An example of how user input should be.
  * `description`: Describing what the command will do.
  * `callback`: The callback which will be invoked with the argument you expect
  to receive.
