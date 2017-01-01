<?php

namespace Nuntius\Plugins;

use Nuntius\NuntiusPlugin;

class Reminder implements NuntiusPlugin {

  public $formats = [
    'when {username} is logged in tell him to {do_something}',
    'Remind me when i\'m logging in to {do_something}',
  ];

  public function action() {
    return 'a';
  }
}
