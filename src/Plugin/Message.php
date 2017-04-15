<?php

namespace Nuntius\Plugin;

use Nuntius\Nuntius;
use Nuntius\NuntiusPluginAbstract;
use Slack\ChannelInterface;
use Slack\User;

/**
 * Class Message.
 *
 * Triggered when a message eas sent.
 */
class Message extends NuntiusPluginAbstract {

  /**
   * {@inheritdoc}
   */
  public function action() {
    $data = $this->data;

    if (!$this->botWasMentioned($data['text'])) {
      return;
    }

    // Check if we in a room or direct message room.
    $target_channel = $this->isDirectMessage() ? $this->client->getDMByUserId($data['user']) : $this->client->getChannelById($data['channel']);

    $target_channel->then(function (ChannelInterface $channel) use ($data) {
      $this->client->send("Give me a second...", $channel);
      sleep(1);

      // Clean the text from bot mentioning.
      $text = str_replace('<@' . $this->getBotUserId() . '> ', '', $data['text']);

      // Look for the matching task handler.
      $task_handler = Nuntius::getTasksManager();

      if (!$info = $task_handler->getMatchingTask($text)) {
        $this->client->send("Sorry. I could not find what you want me to do.", $channel);
        return;
      }

      list($plugin, $callback, $arguments) = $info;

      $plugin
        ->setClient($this->client)
        ->setData($data);

      if ($text = call_user_func_array([$plugin, $callback], $arguments)) {
        $this->client->send($text, $channel);
      }
    });
  }

  /**
   * Determine if the message is DM.
   */
  protected function isDirectMessage() {
    return strpos($this->data['channel'], 'D') === 0;
  }

  /**
   * Checking if the given user ID is a bot ID.
   *
   * @param string $user_id
   *   The user ID.
   *
   * @return bool
   *   True or False.
   */
  protected function isBot($user_id) {
    $result = '';

    $this->client->getUserById($user_id)->then(function(User $user) use (&$result) {
      // @todo add another method to check if nuntius was mentioned and not any
      // bot.
      $result = $user->data['is_bot'];
    });

    return $result;
  }

  /**
   * Checking if the bot was mentioned in the conversation.
   *
   * @param string $text
   *   The text to check.
   *
   * @return bool
   *   True or False.
   */
  protected function botWasMentioned($text) {
    $words = explode(' ', $text);

    foreach ($words as $word) {
      if (!$matches = $this->matchTemplate($word, '/<@(.*)>/')) {
        continue;
      }

      if ($this->isBot(reset($matches))) {
        // We got it! the bot was mentioned.
        return TRUE;
      }
    }

    // No bot was mentioned.
    return FALSE;
  }

  /**
   * Get the bot ID.
   *
   * @return string
   *   The bot ID.
   */
  protected function getBotUserId() {
    $bot_id = '';
    $this->client->getUserByName('nuntius')->then(function (User $user) use (&$bot_id) {
      $bot_id = $user->data['id'];
    });

    return $bot_id;
  }

}
