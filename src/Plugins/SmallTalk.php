<?php

namespace Nuntius\Plugins;

use Nuntius\NuntiusPluginAbstract;

class SmallTalk extends NuntiusPluginAbstract {

  /**
   * @inheritdoc
   */
  protected $category = 'Small talk';

  public $formats = [
    'hi,Hi,Hey,hey,hello,Hello,Shalom,shalom,hello' => [
      'callback' => 'Hi',
      'human_command' => 'hi,Hi,Hey,hey,hello,Hello,Shalom,shalom,hello',
      'description' => 'Several ways to get a random text from me.',
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
