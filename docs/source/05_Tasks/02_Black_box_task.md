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
    $this->reminders->save([
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


## Tasks and multiple bots

In [Multiple bots and context manager](Multiple_bots_and_context_manager.html)
we talked on context manager and how we can handle multiple bots. Now, let's see
how tasks are being handle in when we have multiple bots.

In the section above you can see a command declaration. When the the callback is
a string, and the task manager found that the given text from the user match
a structure of a task the function will be invoke in any bot platform.

The problem is - different platforms have different variables and need different
format of the message the user will get. In order to solve that, the callback 
can be converted into array and will keyed in a structure of 
`platform => callback`. Let's see how the help task solve that:

```php
  /**
   * {@inheritdoc}
   */
  public function scope() {
    return [
      '/help/' => [
        'human_command' => 'help',
        'description' => 'Giving you help',
        'callback' => [
          'slack' => 'slackListOfScopes',
          'facebook' => 'facebookListOfScopes',
        ],
      ],
    ];
  }
  
  /**
   * Get all the tasks and their scope(except for this one).
   */
  public function slackListOfScopes() {
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

  /**
   * A Facebook only text.
   *
   * Facebook allows to send only 3 buttons - this what we will do.
   */
  public function facebookListOfScopes() {
    $send_api = Nuntius::facebookSendApi();

    return $send_api->templates->button
      ->text('hey there! This is the default help response ' .
      'You can try this one and override it later on. ' .
      'Hope you will get some ideas :)')
      ->addButton($send_api->buttons->postBack->title('Say something nice')->payload('something_nice'))
      ->addButton($send_api->buttons->postBack->reset()->title("What's my name?")->payload('what_is_my_name'))
      ->addButton($send_api->buttons->postBack->reset()->title('Toss a coin?')->payload('toss_a_coin'));
  }
```
