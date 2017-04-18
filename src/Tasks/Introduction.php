<?php

namespace Nuntius\Tasks;

use Nuntius\TaskConversationAbstract;
use Nuntius\TaskConversationInterface;

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
    return 'Oh hey! It look that we are not introduced yet. what is your first name?';
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
