<?php

namespace SlackHttpService\Services;

/**
 * Handle the users part of the rest api with slack.
 */
class SlackHttpServiceUsers extends SlackHttpServiceHandlerAbstract {

  /**
   * {@inheritdoc}
   */
  protected $mainApi = 'users';

  /**
   * Get list of user and information.
   *
   * @return \stdClass
   *   The JSON representation of the user list request.
   */
  public function getList() {
    return $this->decodeApiRequest('list');
  }

  /**
   * Get user by ID.
   *
   * @param string $id
   *   The name.
   *
   * @return \stdClass
   *   The JSON representation of the user list request.
   */
  public function getUserById($id) {
    return $this->decodeRequest($this->slackHttpService->requestWithArguments('users.info', ['user' => $id]));
  }

  /**
   * Get user by name.
   *
   * @param $name
   *   The name of the user.
   *
   * @return \stdClass
   *   The JSON representation of the user list request.
   */
  public function getUserByName($name) {
    $users_filtered = array_filter(array_map(function($user) use ($name) {
      if ($user->name == $name) {
        return $user;
      }
      return;
    }, $this->getList()->members));

    return reset($users_filtered);
  }

}
