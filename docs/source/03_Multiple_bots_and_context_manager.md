Nuntius is very slack oriented since this is the first platform it was designed 
for. But, Slack is not the only bot platform and as a framework for bots, 
Nuntius need to support all platforms. And it is.

## Where are you?
Requests can come from a lot of platforms and in order to know how to select the
correct callback we need first to know which platform looked for the matching
task to the text.

For that we have the context manager. It's service designed to keep one variable
through the page request - which platform looked for the matching task to the 
text. It's very easy to use it:

```php
<?php

\Nuntius\Nuntius::getContextManager()->setContext(PLATFORM);
```

You'll need to set the context before triggering looking for matching text, just
like facebook in `\Nuntius\WebhooksRounting\Facebook::response`:
```php
<?php
 if (!empty($_GET['hub_challenge'])) {
    // Validating facebook testing request.
    return new Response($_GET['hub_challenge']);
  }

  Nuntius::getContextManager()->setContext('facebook');

  $this->fbRequest = $this->extractFacebookRequest(json_decode(file_get_contents("php://input")));
  $this->accessToken = Nuntius::getSettings()->getSetting('fb_token');

  $this->sendAPI
    ->setRecipientId($this->fbRequest['sender'])
    ->setAccessToken($this->accessToken);

  if (empty($this->fbRequest['text'])) {

    if (!empty($this->fbRequest['postback'])) {
      $this->sendAPI->sendMessage($this->helpRouter());
    }

    return new Response();
  }

  $task_info = Nuntius::getTasksManager()->getMatchingTask($this->fbRequest['text']);
```

If you want to know the current context just use:
```php
<?php

\Nuntius\Nuntius::getContextManager()->getContext();
```

And that's it.
