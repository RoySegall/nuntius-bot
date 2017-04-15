<?php

namespace Nuntius\Tasks;

use Nuntius\EntityManager;
use Nuntius\NuntiusRethinkdb;
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
  function __construct(NuntiusRethinkdb $db, $task_id, EntityManager $entity_manager) {
    parent::__construct($db, $task_id, $entity_manager);

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

    $rows = $this->db
      ->getTable('reminders')
      ->filter(\r\row('user')->eq($this->data['user']))
      ->run($this->db->getConnection());

    foreach ($rows as $row) {
      $result = $row->getArrayCopy();

      $this->client->getDMByUserId($result['user'])->then(function (DirectMessageChannel $channel) use ($result) {
        // Send the reminder.
        $text = 'Hi! You asked me to remind you: ' . $result['reminder'];
        $this->client->send($text, $channel);

        // Delete the reminder from the DB.
        $this->reminders->delete($result['id']);
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
