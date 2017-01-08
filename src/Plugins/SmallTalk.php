<?php

namespace Nuntius\Plugins;

use Nuntius\NuntiusPluginAbstract;

class SmallTalk extends NuntiusPluginAbstract {

  public $formats = [
    'hi,Hi,Hey,hey,hello,Hello,Shalom,shalom,hello' => [
      'callback' => 'Hi',
      'description' => '',
    ],
    '/what can you do/' => [
      'callback' => 'help',
      'description' => '',
    ],
    '/help/' => [
      'callback' => 'help',
      'description' => '',
    ],
  ];

  /**
   * Random stuff.
   */
  public function Hi() {
    $answers = [
      'Hi there!',
      'Hello to you too',
      "Well hello there!",
      "if you gotta shoot shoot don't talk :gun:",
    ];

    shuffle($answers);

    return reset($answers);
  }

  /**
   * All the stuff that nuntius can do.
   */
  public function help() {
    return "Well.. I can do a lot of stuff! Soon you all know what can I do";
  }

}
