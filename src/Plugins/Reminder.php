<?php

namespace Nuntius\Plugins;

use Nuntius\Nuntius;
use Nuntius\NuntiusPluginAbstract;

class Reminder extends NuntiusPluginAbstract {

  protected $category = 'Reminders';

  public $formats = [
    '/remind me next time I log into (.*)/' => [
      'callback' => 'RemindMe',
      'human_command' => 'remind me next time I log into REMINDER',
      'description' => 'The next time you will log in, I will remind of what you'
      . ' asked',
    ],
    '/when (.*) is logged in tell him (.*)/' => [
      'callback' => 'RemindTo',
      'human_command' => 'when USERNAME is logged in tell him STUFF',
      'description' => 'Next time the username logs in I will remind him'
      . ' of what you asked. Don\'t add the @ sign before the username.',
    ],
    '/forget about the reminders for (.*)/' => [
      'callback' => 'deleteReminderForFrom',
      'human_command' => 'forget about the reminders for USERNAME',
      'description' => 'Next time the username will log in I will remind him'
      . ' of what you asked. Don\'t add the @ sign before the username.',
    ],
    '/delete all the reminders I asked from you/' => [
      'callback' => 'DeleteAllReminderOfUser',
      'human_command' => 'delete all the reminders I asked from you',
      'description' => 'I will delete all the reminders you asked from me.',
    ],
  ];

  /**
   * Adding a reminder for some one.
   *
   * @param $to
   *   The user we need to remind.
   * @param $remind
   *   The reminder.
   *
   * @return string
   *   The text to return after the action was done.
   */
  public function RemindTo($to, $remind) {
    Nuntius::getRethinkDB()->addEntry('reminders', [
      'to' => trim($to),
      'message' => trim($this->author) . ' told me to tell you ' . trim($remind),
      'author' => trim($this->author),
    ]);

    return "Don't worry! I got your back. I'll send him a reminder.";
  }

  /**
   * Remind my self.
   *
   * @param $remind
   *   The remind.
   *
   * @return string
   *   The text to return after the action was done.
   */
  public function RemindMe($remind) {
    Nuntius::getRethinkDB()->addEntry('reminders', [
      'to' => trim($this->author),
      'message' => 'You asked me to remind about ' . trim($remind),
      'author' => trim($this->author),
    ]);

    return "OK! I will remind you next you'll log in.";
  }

  /**
   * Deleting all the reminders for a user.
   *
   * @param $to
   *   The user name.
   *
   * @return string
   *   The text to return after the action was done.
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

    return "Roger out! The reminder for " . $to . " was removed.";
  }

  /**
   * Delete all the messages a user asked.
   *
   * @return string
   *   The text to return after the action was done.
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

    return "What reminder? :wink:";
  }

}
