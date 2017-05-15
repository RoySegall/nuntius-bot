One way to communicate with Nuntius is via text. First, let's have a look at the
`hooks.yml` file:
```yml
tasks:
  reminders: '\Nuntius\Tasks\Reminders'
  help: '\Nuntius\Tasks\Help'
  introduction: '\Nuntius\Tasks\Introduction'
```

The tasks plugin needs to declare to which text it needs to response AKA scope:

There two types of plugins:
1. Black box task - A task that needs arguments, or not, and does a simple job: 
set a reminder for later.
2. Conversation task - A task which depends on information and can get it by
asking the user a couple of questions. Each conversation task has a 
conversation scope:
  * Forever - a scope that likely won't change in the near future: List of the
  user's team members.
  * Temporary - A scope that we don't need to keep forever: What you want to eat
  for lunch. **But** a temporary scope may not be relevant forever but we might
  want to use in the future. We would likely want to keep the places the user
  invited food from so we could suggest that in the past.

Let's dive into the code.

### Black box task
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
  
### Conversation task
Let's look first at the code and explain how to write the plugin:
```php
<?php

namespace Nuntius\Tasks;


/**
 * Remind to the user something to do.
 */
class Introduction extends TaskConversationAbstract implements TaskConversationInterface {

  /**
   * {@inheritdoc}
   */
  public function scope() {
    return [
      '/nice to meet you/' => [
        'human_command' => 'nice to meet you',
        'description' => 'We will do a proper introduction',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function conversationScope() {
    return 'forever';
  }

  /**
   * Get the user first name.
   */
  public function questionFirstName() {
    return 'Oh hey! It looks that we are not introduced yet. what is your first name?';
  }

  /**
   * Get the last name of the user.
   */
  public function questionLastName() {
    return 'what is your last name?';
  }

  /**
   * {@inheritdoc}
   */
  public function collectAllAnswers() {
    return 'Well, ' . $this->answers['FirstName'] . ' ' . $this->answers['LastName'] . ', it is a pleasure.';
  }

}

```

First, it's important to implement the `TaskConversationInterface` interface. 
This is the way we recognize this a conversation task.

Similar to the black box task we do define a scope but in this case, we don't
define a callback. That's because nuntius will ask the question by a naming
conventions method: methods with a `question` prefix will be invoked(similar to 
Unit test). The method needs to return the text of the question. The questions 
will be triggered by the order in the class - so keep in a rational order of 
methods.

When nuntius collected all the answers, the `collectAllAnswers` will be invoked.
The answers will be available in the `answers` property with the matching name
of the method which holds the question but without the `question` prefix.

In case something got in the way and the user lost his internet connection or
the server went down the answers won't get lost. The answers stored in the DB
except for a temporary context conversation. The answers will move into an
archive and won't be available for next time the conversation will start.
