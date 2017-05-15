Integration with slack can be achieved in various ways. Nuntius implementing the
integration via WebSocket and push events AKA RTM events. For any operation on
slack, there is a matching RTM event. You can look on the list 
[here](https://api.slack.com/rtm#events).

Let's see how to interact with the message events. In the `hooks.yml` we have
the `events` section:
```yml
events:
  presence_change: '\Nuntius\Plugin\PresenceChange'
  message: '\Nuntius\Plugin\Message'
```

The `message` key paired with the namespace for the class that needs to 
implement the logic for the events. Let's have a look at the code:

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
    // code here...
  }
  
}
```

Every time someone will send a message the action method will be invoked.

### On presence change
For now, until we will switch to Symfony event dispatcher, events can response
to presence change, AKA when the user logged out or in. We use that option to
notify the users for the reminders.

Let's look on how the message:

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
  public function actOnPresenceChange() {
    if ($this->data['presence'] == 'away') {
      return;
    }

    $rows = $this->db
      ->getTable('reminders')
      ->filter(\r\row('user')->eq($this->data['user']))
      ->run($this->db->getConnection());

    foreach ($rows as $row) {
      $result = $row->getArrayCopy();

      $this->client->getDMByUserId($result['user'])->then(function (DirectMessageChannel $channel) use ($result) {
        // Send the reminder.
        $text = 'Hi! You asked me to remind you: ' . $result['reminder'];
        $this->client->send($text, $channel);

        // Delete the reminder from the DB.
        $this->reminders->delete($result['id']);
      });
    }
  }
  
}

```

In this case, we are looking for reminders which the user set and send it as a
private message.
