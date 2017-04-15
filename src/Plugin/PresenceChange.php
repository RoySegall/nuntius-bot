<?php

namespace Nuntius\Plugin;

use Nuntius\Nuntius;
use Nuntius\NuntiusPluginAbstract;

/**
 * Class PresenceChange.
 *
 * Triggered when the user's status has changed.
 */
class PresenceChange extends NuntiusPluginAbstract {

  /**
   * {@inheritdoc}
   */
  public function action() {

    $task_handler = Nuntius::getTasksManager();

    foreach ($task_handler->getTasks() as $task) {
      $task
        ->setClient($this->client)
        ->setData($this->data)
        ->actOnPresenceChange();
    }
  }

}
