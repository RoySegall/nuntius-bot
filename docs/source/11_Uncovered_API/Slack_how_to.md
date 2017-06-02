How to send a message to the user:

```php
<?php

namespace Nuntius\Plugin;

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
      $this->client->getDMByUserId('USER_ID')->then(function (ChannelInterface $channel) {
       $this->client->send('Hi user!', $channel);
     });
    }
}
```

Send message in a room:
```php
<?php

namespace Nuntius\Plugin;

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
    $this->client->getChannelById('ROOM_ID')->then(function (ChannelInterface $channel) {
      $this->client->send('Hi there room members', $channel);
    });
  }
}
```

Send a message to a user when outside a room context AKA HTTP RPC-style:

```php
<?php
$slack_http = new SlackHttpService();
$slack = $slack_http->setAccessToken(Nuntius::getSettings()->getSetting('access_token'));
$im_room = $slack->Im()->getImForUser($slack->Users()->getUserByName(strtolower($info['username'])));
$message = new SlackHttpPayloadServicePostMessage();
$message
  ->setChannel($im_room)
  ->setText($info['text']);

// Posting the message.
$slack->Chat()->postMessage($message);
```

For more options look on 
`\Nuntius\Examples\GitHubOpened\NuntiusGitHubOpenedExample::postMessage`
