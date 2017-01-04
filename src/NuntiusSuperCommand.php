<?php

namespace Nuntius;

use PhpSlackBot\Command\BaseCommand;

class NuntiusSuperCommand extends BaseCommand {

  protected function configure() {
  }

  protected function execute($data, $context) {

    $username = $this->getUserNameFromUserId($data['user']);
    $data['username'] = $username;

    Nuntius::getRethinkDB()->addEntry('logs', $data);

    $nuntius = new \Nuntius\Nuntius();
    $nuntius->addPlugins(New \Nuntius\Plugins\Reminder());

    list($author, $message) = explode(': ', $data['content']);
    $nuntius
      ->setAuthor($author)
      ->getPlugin($message);


    // Move to plugin actions.


    if ($data['type'] == 'presence_change' && $data['presence'] == 'active') {

      $results = Nuntius::getRethinkDB()->getTable('reminders')
        ->filter(\r\row('to')->eq($username))
        ->run(Nuntius::getRethinkDB()->getConnection());

      foreach ($results as $result) {

        var_dump($this->getIMChannel($this->getIdFromUserName($result['to'])));
        var_dump($this->getIdFromUserName($result['to']));
        var_dump($result['to']);
        $this->send($this->getIMChannel($this->getIdFromUserName($result['to'])), $result['to'], $result['author'] . ' told me to tell you ' . $result['remind']);

        // todo: delete after sending.
      }
    }

    if ($data['type'] == 'desktop_notification') {
//      if ($data['subtitle'] == 'itamargronich' || $data['subtitle'] == 'roysegall') {
//        $this->send($data['channel'], $data['subtitle'], 'עשית בר פיס היום גבר?');
//      }
    }
  }

  public function getIdFromUserName($username) {
    foreach ($this->getCurrentContext()['users'] as $user) {
      if ($user['name'] == $username) {
        return $user['id'];
      }
    }
  }

  public function getIMChannel($userId) {
    $client = new \GuzzleHttp\Client();
    $url = 'https://slack.com/api/im.open?';
    $url.= 'token='.\Nuntius\Nuntius::getSettings()['bot_id'];
    $url.= '&user='.$userId;
    $res = $client->request('GET', $url);
    $obj = json_decode($res->getBody(), true);

    if (isset($obj['channel']['id'])) {
      return $obj['channel']['id'];
    }
    else {
      return "";
    }
  }

  public function nuntiusSendMessage($channel, $subtitle, $text) {
    $this->send($channel, $subtitle, $text);
  }
}
