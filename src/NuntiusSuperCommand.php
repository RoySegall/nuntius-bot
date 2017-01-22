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

    list($message, $author) = $this->getAuthorAndMessage($data);

    // Get the matching plugin.
    if (in_array($data['type'], ['desktop_notification', 'message'])) {
      $this->sendReactionMessage($data, $author, $message);
    }

    if ($data['type'] == 'presence_change' && $data['presence'] == 'active') {
      $this->WelcomeMessageFire($data);
      $this->NotificationFire($data);
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

  /**
   * Fir up a message When the bot was notified or received a PM.
   *
   * @param $data
   *   Information about the last action.
   * @param $author
   *   The user who wrote the message.
   * @param $message
   *   The content of the message.
   */
  protected function sendReactionMessage($data, $author, $message) {
    $text = $this->nuntius
      ->setAuthor($author)
      ->getPlugin($message);

    if ($text) {
      if (is_array($text)) {
        foreach ($text as $senctences) {
          $this->freeMessage($data['channel'], $senctences);
        }
      } else {
        $this->freeMessage($data['channel'], $text);
      }
    }
  }

  /**
   * The user logged in. Any stuff we need to tell him?
   *
   * @param $data
   *   Information about the event.
   */
  protected function NotificationFire($data) {
    $results = Nuntius::getRethinkDB()
      ->getTable('reminders')
      ->filter(\r\row('to')->eq($data['username']))
      ->run(Nuntius::getRethinkDB()->getConnection());

    foreach ($results as $result) {
      $this->send($this->getIMChannel($this->getIdFromUserName($result['to'])), $result['to'], $result['message']);

      // The reminder no longer have any purpose. Delete it.
      Nuntius::getRethinkDB()
        ->getTable('reminders')
        ->get($result['id'])
        ->delete()
        ->run(Nuntius::getRethinkDB()->getConnection());
    }
  }

  /**
   * When the user is logged in for the first time, greet him.
   *
   * @param $data
   *   Information about the event.
   */
  protected function WelcomeMessageFire($data) {
    $username = $this->getUserNameFromUserId($data['user']);
    $channel = $this->getIMChannel($data['user']);

    $results = Nuntius::getRethinkDB()
      ->getTable('users')
      ->filter(\r\row('username')->eq($username))
      ->filter(\r\row('greeted')->eq(TRUE))
      ->run(Nuntius::getRethinkDB()->getConnection());

    if (!$results->toArray()) {

      $texts = [
        "Hi there! I am nuntius.",
        "This is the first time we see each other. Isn't that exciting? A new friend!",
        "I can assist you with couple of ways. Just say `help` and I'll show what I can do.",
      ];
      foreach ($texts as $text) {
        $this->send($channel, $username, $text);
      }

      Nuntius::getRethinkDB()->addEntry('users', [
        'username' => $username,
        'greeted' => TRUE,
      ]);
    }
  }

  /**
   * Get the author and the message from the event data.
   *
   * @param $data
   *   Data relate to the event.
   *
   * @return array
   *   Return array with the message and the username.
   */
  protected function getAuthorAndMessage(&$data) {
    $username = $this->getUserNameFromUserId($data['user']);

    if ($data['type'] == 'message') {
      // This is a normal text with out the bot was mentioned.
      $message = $data['text'];
      $data['username'] = $username;
      return array($message, $data['username']);
    }
    else {
      // The bot was mentioned.
      list($author, $message) = explode(': ', $data['content']);
      return array($message, $author);
    }
  }

}
