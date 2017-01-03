<?php

namespace Nuntius;

use PhpSlackBot\Command\BaseCommand;

class NuntiusSuperCommand extends BaseCommand {

  protected function configure() {
  }

  protected function execute($data, $context) {

    $username = $this->getUserNameFromUserId($data['user']);
    $data['username'] = $username;
    var_dump($data);

    if ($data['type'] == 'presence_change' && $data['presence'] == 'active') {
      $username = $this->getUserNameFromUserId($data['user']);
      $imchannel = $this->getIMChannel($data['user']);

      if ($username == 'itamargronich') {
        $this->send($imchannel, $username, "שלום לך!");
      }

      if ($username == 'roysegall') {
        $this->send($imchannel, $username, "Hi there georgce!");
      }

      if ($username == 'rachel') {
        $this->send($imchannel, $username, "How You Doin'..? https://youtu.be/43wkqM27z2E");
      }

      if ($username == 'da_vi_t') {
        $this->send($imchannel, $username, "גבר... מה עם ברפיס?");
      }
    }

    // Some one ping the bot.
    if ($data['type'] == 'desktop_notification') {
      if ($data['subtitle'] == 'itamargronich' || $data['subtitle'] == 'roysegall') {
        $this->send($data['channel'], $data['subtitle'], 'עשית בר פיס היום גבר?');
      }
    }
  }

  public function getIMChannel($userId) {
    $client = new \GuzzleHttp\Client();
    $url = 'https://slack.com/api/im.open?';
    $url.= 'token='.'xoxb-122081602870-7snjp9RHBDrEZ7l9j47qfXH2';
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
