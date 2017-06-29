So far, you set up the infrastructure for the updates. Now, let's see what going
on there so you could implement a custom logic for your project.

## DB

One of the things we did was to add an entity that will keep track the recipient
IDs:

```yml
entities:
  fb_reminders: '\Nuntius\Examples\Drupal\FbReminders'

# List of updates.
updates:
  2: '\Nuntius\Examples\Drupal\AddFbReminder'
```

For a new install you don't need to update path because - when installing 
nuntius all the entities are setted up for us. The update path dmonstrate how
to add a new table for existing installations:
```php
  /**
   * Describe what the update going to do.
   *
   * @return string
   *   What the update going to do.
   */
  public function description() {
    return 'Adding the FB reminders entity';
  }

  /**
   * Running the update.
   *
   * @return string
   *   A message for what the update did.
   */
  public function update() {
    Nuntius::getDb()->getOperations()->tableCreate('fb_reminders');
    return 'Adding the table.';
  }
```

## Respond to text
The user will probably asks us to `Manage updates`, for that we set a task 
handler:
```yml
# Manage tasks.
tasks:
  fb_manage_updates: '\Nuntius\Examples\Drupal\FacebookUpdatesManage'
```

When he will send the message we will need to respond to the text:
```php
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
```

## Webhooks routing.
We added two new webhooks routing:

```yml
# List of webhooks and te matcher handler.
webhooks_routing:
  'drupal': '\Nuntius\Examples\Drupal\Drupal'
  'facebook-drupal': '\Nuntius\Examples\Drupal\FacebookDrupal'
```

The webhooks will validate the token and post to Facebook/Slack information
about the new content.

## Handle the user choices

The task returned a text with two payload buttons. But how we going to act? By
defining the FB postbacks handlers we can register or un-register the user 
from updates through the messenger platform. Let's have a look on two examples.

`\Nuntius\Examples\Drupal\RegisterMe`:

```php
  /**
   * {@inheritdoc}
   */
  public function postBack() {
    // Check that the recipient is already registered.
    if ($this->queryForRecipient($this->fbRequest['sender'])) {
      return;
    }

    Nuntius::getEntityManager()->get('fb_reminders')->save(['recipient_id' => $this->fbRequest['sender']]);
    return 'Got it! You will be notified on new stuff';
  }
```

`\Nuntius\Examples\Drupal\UnRegisterMe`:

```php
  /**
   * {@inheritdoc}
   */
  public function postBack() {
    $row = $this->queryForRecipient($this->fbRequest['sender']);

    Nuntius::getEntityManager()->get('fb_reminders')->delete($row[0]['id']);

    return "You don't want to get updates. That's OK. See you in the future";
  }
```

## How the posting to FB and slack works
After setting up all the stuff, let's see how the webhook routing post in the
platform they design for.

`\Nuntius\Examples\Drupal\Drupal`:
```php
  /**
   * {@inheritdoc}
   */
  protected function trigger() {
    // Get the slack http service.
    $slack_http = new SlackHttpService();
    $slack = $slack_http->setAccessToken(Nuntius::getSettings()->getSetting('access_token'));

    // Build the attachment.
    $attachment = new SlackHttpPayloadServiceAttachments();
    $attachment
      ->setColor('#36a64f')
      ->setTitle($this->payload->title)
      ->setTitleLink($this->url);

    if (!empty($this->payload->body->und[0]->value)) {
      $attachment->setText($this->payload->body->und[0]->value);
    }

    $attachments[] = $attachment;

    // Build the payload of the message.
    $message = new SlackHttpPayloadServicePostMessage();
    $message
      ->setChannel($this->slackRoom)
      ->setAttachments($attachments)
      ->setText('A new content on the site! Yay!');

    // Posting the message.
    $slack->Chat()->postMessage($message);

    return new Response();
  }
```

And for facebook we have the next code, `\Nuntius\Examples\Drupal\FacebookDrupal`:
```php
  /**
   * {@inheritdoc}
   */
  protected function trigger() {
    $subtitle = !empty($this->payload->body->und[0]->value) ? $this->payload->body->und[0]->value : '';

    // Look for registered users from the given URL.
    if (!$users = Nuntius::getDb()->getQuery()->table('fb_reminders')->execute()) {
      return new Response();
    }

    // Prepare the send API object.
    $send_api = Nuntius::facebookSendApi();
    $send_api->setAccessToken(Nuntius::getSettings()->getSetting('fb_token'));

    $element = $send_api->templates->element;
    $payload = $send_api->templates->generic
      ->addElement(
        $element
          ->title($this->payload->title)
          ->subtitle($subtitle)
          ->addButton($send_api->buttons->url->title('Take me there!')->url($this->url))
      );

    // Loop over the users.
    foreach ($users as $user) {
      $send_api
        ->setRecipientId($user['recipient_id'])
        ->sendMessage($payload);
    }

    return new Response();
  }
```