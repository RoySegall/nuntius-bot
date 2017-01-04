<?php

namespace Nuntius\Plugins;

use Nuntius\Nuntius;
use Nuntius\NuntiusPluginAbstract;

class Reminder extends NuntiusPluginAbstract {

  public $formats = [
    '/remind me next time I logging in to (.*)/' => [
      'callback' => 'RemindMe',
      'description' => '',
    ],
    '/when (.*) is logged in tell him (.*)/' => [
      'callback' => 'RemindTo',
      'description' => '',
    ],
    '/forget about the reminders for (.*)/' => [
      'callback' => 'deleteReminderForFrom',
      'description' => '',
    ],
    '/delete all the reminders I asked from you/' => [
      'callback' => 'DeleteAllReminderOfUser',
      'description' => '',
    ],
  ];

  /**
   * Adding a reminder for some one.
   *
   * @param $to
   *   The user we need to remind.
   * @param $remind
   *   The reminder.
   */
  public function RemindTo($to, $remind) {
    Nuntius::getRethinkDB()->addEntry('reminders', [
      'to' => trim($to),
      'message' => trim($this->author) . ' told me to tell you ' . trim($remind),
      'author' => trim($this->author),
    ]);
  }

  /**
   * Remind my self.
   *
   * @param $remind
   *   The remind.
   */
  public function RemindMe($remind) {
    Nuntius::getRethinkDB()->addEntry('reminders', [
      'to' => trim($this->author),
      'message' => 'You told me to remind about ' . trim($remind),
      'author' => trim($this->author),
    ]);
  }

  /**
   * Deleting all the reminders for a user.
   *
   * @param $to
   *   The user name.
   */
  public function deleteReminderForFrom($to) {
    // The user logged in. Any stuff we need to tell him?
    $results = Nuntius::getRethinkDB()->getTable('reminders')
      ->filter(\r\row('to')->eq($to))
      ->run(Nuntius::getRethinkDB()->getConnection());

    foreach ($results as $result) {
      // The reminder no longer have any purpose. Delete it.
      Nuntius::getRethinkDB()->getTable('reminders')
        ->get($result['id'])
        ->delete()
        ->run(Nuntius::getRethinkDB()->getConnection());
    }
  }

  /**
   * Delete all the messages a user asked.
   */
  public function DeleteAllReminderOfUser() {
    // The user logged in. Any stuff we need to tell him?
    $results = Nuntius::getRethinkDB()->getTable('reminders')
      ->filter(\r\row('author')->eq($this->author))
      ->run(Nuntius::getRethinkDB()->getConnection());

    foreach ($results as $result) {
      // The reminder no longer have any purpose. Delete it.
      Nuntius::getRethinkDB()->getTable('reminders')
        ->get($result['id'])
        ->delete()
        ->run(Nuntius::getRethinkDB()->getConnection());
    }
  }

}
