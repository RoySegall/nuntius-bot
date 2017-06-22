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
