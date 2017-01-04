<?php

namespace Nuntius\Plugins;

use Nuntius\Nuntius;
use Nuntius\NuntiusPluginAbstract;

class Reminder extends NuntiusPluginAbstract {

  public $formats = [
    '/Remind me when i\'m logging in to (.*)/' => [
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
    '/forget about the last reminder for (.*)/' => [
      'callback' => 'RemindTo',
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
   * @param $remind
   */
  public function RemindTo($to, $remind) {

    Nuntius::getRethinkDB()->addEntry('reminders', [
      'to' => trim($to),
      'remind' => trim($remind),
      'author' => trim($this->author),
    ]);

  }

}
