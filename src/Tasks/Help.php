<?php

namespace Nuntius\Tasks;

use Nuntius\Nuntius;
use Nuntius\TaskBaseAbstract;
use Nuntius\TaskBaseInterface;
use pimax\FbBotApp;
use pimax\Menu\LocalizedMenu;
use pimax\Menu\MenuItem;

/**
 * Remind to the user something to do.
 */
class Help extends TaskBaseAbstract implements TaskBaseInterface {

  /**
   * {@inheritdoc}
   */
  public function scope() {
    return [
      '/help/' => [
        'human_command' => 'help',
        'description' => 'Giving you help',
        'callback' => [
          'slack' => 'slackListOfScopes',
          'facebook' => 'facebookListOfScopes',
        ],
      ],
    ];
  }

  /**
   * Get all the tasks and their scope(except for this one).
   */
  public function slackListOfScopes() {
    $task_manager = Nuntius::getTasksManager();

    $text = [];

    foreach ($task_manager->getTasks() as $task_id => $task) {
      if ($task_id == 'help') {
        continue;
      }

      foreach ($task->scope() as $scope) {
        $text[] = '`' . $scope['human_command'] . '`: ' . $scope['description'];
      }
    }

    return implode("\n", $text);
  }

  /**
   * A Facebook only text.
   *
   * Facebook allows to send only 3 buttons - this what we will do.
   */
  public function facebookListOfScopes() {
    $send_api = Nuntius::facebookSendApi();

    return $send_api->templates->button
      ->text('hey there! This is the default help response ' .
      'You can try this one and override it later on. ' .
      'Hope you will get some ideas :)')
      ->addButton($send_api->buttons->postBack->title('Say something nice')->payload('something_nice'))
      ->addButton($send_api->buttons->postBack->title("What's my name?")->payload('what_is_my_name'))
      ->addButton($send_api->buttons->postBack->title('Toss a coin?')->payload('toss_a_coin'));
  }

}
