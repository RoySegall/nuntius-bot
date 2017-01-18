<?php

namespace Nuntius;

use GuzzleHttp\Client;
use PhpSlackBot\Command\BaseCommand;

class NuntiusSuperCommand extends BaseCommand {

  /**
   * Nuntius instance.
   *
   * @var Nuntius
   */
  protected $nuntius;

  /**
   * @param Nuntius $nuntius
   */
  public function setNuntius(Nuntius $nuntius) {
    $this->nuntius = $nuntius;
  }

  /**
   * @return Nuntius
   */
  public function getNuntius() {
    return $this->nuntius;
  }

  protected function configure() {
  }

  protected function execute($data, $context) {

    $username = $this->getUserNameFromUserId($data['user']);
    $data['username'] = $username;

    list($author, $message) = explode(': ', $data['content']);

    // Get the matching plugin.

    if ($data['type'] == 'desktop_notification') {
      $text = $this->nuntius
        ->setAuthor($author)
        ->getPlugin($message);

      if ($text) {
        if (is_array($text)) {
          foreach ($text as $senctences) {
            $this->freeMessage($data['channel'], $senctences);
          }
        }
        else {
          $this->freeMessage($data['channel'], $text);
        }
      }
    }

    // todo: Move to plugin actions.
    if ($data['type'] == 'presence_change' && $data['presence'] == 'active') {

      // The user logged in. Any stuff we need to tell him?
      $results = Nuntius::getRethinkDB()->getTable('reminders')
        ->filter(\r\row('to')->eq($data['username']))
        ->run(Nuntius::getRethinkDB()->getConnection());

      foreach ($results as $result) {
        $this->send($this->getIMChannel($this->getIdFromUserName($result['to'])), $result['to'], $result['message']);

        // The reminder no longer have any purpose. Delete it.
        Nuntius::getRethinkDB()->getTable('reminders')
          ->get($result['id'])
          ->delete()
          ->run(Nuntius::getRethinkDB()->getConnection());
      }
    }

    // Log all the stuff. For debugging and records.
    Nuntius::getRethinkDB()->addEntry('logs', $data);
  }

  /**
   * Get the ID if a user from the username.
   *
   * @param $username
   *   The username. Usually author.
   *
   * @return string
   *   The ID of the user.
   */
  public function getIdFromUserName($username) {
    foreach ($this->getCurrentContext()['users'] as $user) {
      if ($user['name'] == $username) {
        return $user['id'];
      }
    }
  }

  /**
   * Get the ID of an IM channel between nuntius and the user.
   *
   * @param $userId
   *   The user ID.
   *
   * @return string
   *   The ID of the user.
   */
  public function getIMChannel($userId) {
    $client = new Client();
    $url = 'https://slack.com/api/im.open?';
    $url.= 'token=' . Nuntius::getSettings()['bot_id'];
    $url.= '&user=' . $userId;
    $res = $client->request('GET', $url);
    $obj = json_decode($res->getBody(), true);

    if (isset($obj['channel']['id'])) {
      return $obj['channel']['id'];
    }
  }

  public function nuntiusSendMessage($channel, $subtitle, $text) {
    $this->send($channel, $subtitle, $text);
  }

  public function freeMessage($channel, $message) {
      $response = array(
        'id' => time(),
        'type' => 'message',
        'channel' => $channel,
        'text' => $message,
      );
      $this->getClient()->send(json_encode($response));
  }
}
