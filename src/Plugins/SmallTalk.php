<?php

namespace Nuntius\Plugins;

use Nuntius\NuntiusPluginAbstract;

class SmallTalk extends NuntiusPluginAbstract {

  protected $category = 'Small talk';

  public $formats = [
    'hi,Hi,Hey,hey,hello,Hello,Shalom,shalom,hello' => [
      'callback' => 'Hi',
      'description' => '',
    ],
    '/what can you do/' => [
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

}
