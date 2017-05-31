<?php

namespace Nuntius\Tasks;

use Nuntius\Db\DbQueryHandlerInterface;
use Nuntius\EntityManager;
use Nuntius\TaskBaseAbstract;
use Nuntius\TaskBaseInterface;
use Slack\DirectMessageChannel;

/**
 * Remind to the user something to do.
 */
class Reminders extends TaskBaseAbstract implements TaskBaseInterface {

  /**
   * Reminder entity.
   *
   * @var \Nuntius\Entity\Reminders
   */
  protected $reminders;

  /**
   * {@inheritdoc}
   */
  function __construct(DbQueryHandlerInterface $query, $task_id, EntityManager $entity_manager) {
    parent::__construct($query, $task_id, $entity_manager);

    $this->reminders = $this->entityManager->get('reminders');
  }

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
   * {@inheritdoc}
   */
  public function actOnPresenceChange() {
    if ($this->data['presence'] == 'away') {
      return;
    }

    $rows = $this->query
      ->table('reminders')
      ->condition('user', $this->data['user'])
      ->execute();

    foreach ($rows as $row) {
      $this->client->getDMByUserId($row['user'])->then(function (DirectMessageChannel $channel) use ($row) {
        // Send the reminder.
        $text = 'Hi! You asked me to remind you: ' . $row['reminder'];
        $this->client->send($text, $channel);

        // Delete the reminder from the DB.
        $this->reminders->delete($row['id']);
      });
    }
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
    $this->reminders->insert([
      'reminder' => $reminder,
      'user' => $this->data['user'],
    ]);

    return 'OK! I got you covered!';
  }

}
