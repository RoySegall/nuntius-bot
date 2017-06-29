<?php

namespace Nuntius\Examples\Drupal;

use Nuntius\Nuntius;
use Nuntius\TaskBaseAbstract;
use Nuntius\TaskBaseInterface;

/**
 * Manage update in FB.
 */
class FacebookUpdatesManage extends TaskBaseAbstract implements TaskBaseInterface {

  /**
   * {@inheritdoc}
   */
  public function scope() {
    return [
      '/Manage updates/' => [
        'human_command' => 'Manage updates',
        'description' => 'Managing helps in Facebook',
        'callback' => [
          'facebook' => 'showUpdatesOptions'
        ],
      ],
    ];
  }

  /**
   * Manage updates options.
   */
  public function showUpdatesOptions() {
    $send_api = Nuntius::facebookSendApi();

    return $send_api->templates->button
      ->text('What do you want to do?')
      ->addButton($send_api->buttons->postBack->title('Update me')->payload('register_me'))
      ->addButton($send_api->buttons->postBack->title("Don't update me")->payload('un_register_me'));
  }

}
