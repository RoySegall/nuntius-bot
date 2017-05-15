### Github

Yes, there are a lot of slack integrations but most of them pretty generic. 
They don't provide a real feedback from the bot - bad information in the PR or
incorrect information in the issue body. Let's have a look on how to set the 
integration:
```yml
webhooks:
  github:
    opened: '\Nuntius\Webhooks\GitHub\Opened'
```

Now, let's have a look at the code:
```php

<?php

/**
 * Acting upon issue or pull request opening.
 */
class Opened extends GitHubWebhooksAbstract implements GitHubWebhooksInterface {

  /**
   * {@inheritdoc}
   */
  public function act() {
    $payload = $this->data;
    $key = !empty($payload->pull_request) ? 'pull_request' : 'issue';

    $payload = [
      'event' => 'open',
      'type' => $key,
      'user' => $payload->{$key}->user->login,
      'title' => $payload->{$key}->title,
      'body' => $payload->{$key}->body,
    ];

    $this->logger->insert([
      'logging' => 'opened_' . $key,
      'payload' => $payload,
    ]);
  }

}
```

For now, there is just a logging event. After adding Symfony event dispatcher,
you could write a better integration.

### Post act
You can add a logic to the controller that will act after the act method was 
triggered. That's more suitable for logging stuff rather than implementing a 
logic:

```php
<?php

abstract class GitHubWebhooksAbstract implements GitHubWebhooksInterface {

  /// ...
  
  /**
   * {@inheritdoc}
   */
  public function postAct() {
    $this->logger->insert((array) $this->getData());
  }

}
```
