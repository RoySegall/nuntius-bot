You set up as you saw in 
[Configure the integration](Webhooks/Facebook/Configure_the_integration.html).

If you'll write something like `Hey` or `Hello` you'll get something like that:

![Facebook ste 3](../../images/facebook/engage-1.png)

But if you'll write `help` you'll get something like that:

![Facebook ste 3](../../images/facebook/engage-2.png)

## Texts and postbacks

The example above implemented in two parts. The first is the response to the 
text. In slack, the help text bring all the tasks which available to the user
but the facebook platforms works a bit different - you cannot provide all the
tasks as button due to buttons limitation. 

That's why a different approach was taken - provide 3 button with nice actions.
Let's see how the text implemented:
```php
  /**
   * A Facebook only text.
   *
   * Facebook allows to send only 3 buttons - this what we will do.
   */
  public function facebookListOfScopes() {
    $send_api = Nuntius::facebookSendApi();

    return $send_api->templates->button
      ->text('hey there! This is the default help response ' .
      'You can try this one and override it later on. ' .
      'Hope you will get some ideas :)')
      ->addButton($send_api->buttons->postBack->title('Say something nice')->payload('something_nice'))
      ->addButton($send_api->buttons->postBack->title("What's my name?")->payload('what_is_my_name'))
      ->addButton($send_api->buttons->postBack->title('Toss a coin?')->payload('toss_a_coin'));
  }
```

What did we got here? A text with post backs buttons. When clicking on a 
postback button, Facebook will send a webhook with that information. But how can
you respond? As always, nuntius got you covered and provide a FB postback 
manager. The implementation is easy. Let's see how the postbacks implemented in 
`hooks.yml`:
```yml
# List of FB postback handlers.
fb_postbacks:
  'something_nice': '\Nuntius\FacebookPostBacks\SomethingNice'
  'toss_a_coin': '\Nuntius\FacebookPostBacks\TossACoin'
  'what_is_my_name': '\Nuntius\FacebookPostBacks\WhatIsMyName'
```

The key is the payload and the value is the namespace of the class. Let's see an
example for a class:
```php
<?php

class SomethingNice extends FacebookPostBackAbstract implements FacebookPostBackInterface {

  /**
   * {@inheritdoc}
   */
  public function postBack() {
    $texts = [
      'You look lovely!',
      'Usually you wakes up looking good. Today, you took it to the next level!',
      'Hey there POTUS... sorry! thought you are some one else...',
    ];

    shuffle($texts);
    return reset($texts);
  }

}
```
For more examples, please go to the [Drupal](http://localhost:3000/Use_cases/Drupal/Intro.html)
use case.

## What's the API?

Sending messages over the Messenger platform is described in the 
[Send API](https://developers.facebook.com/docs/messenger-platform/send-api-reference)
under the Messenger platform. Sending a text is easy but if you'll look on a
[Templates buttons](https://developers.facebook.com/docs/messenger-platform/send-api-reference/button-template)
you'll be confuse and maybe suffer a light vertigo feeling.

Don't worry, Nuntius uses the [facebook-messenger-send-api](https://github.com/RoySegall/facebook-messenger-send-api)
which makes your life much easy. For example, in order to send a button 
templates you'll need to send this complex JSON:
```JSON
{
  "attachment":{
    "type":"template",
    "payload":{
      "template_type":"button",
      "text":"What do you want to do next?",
      "buttons":[
        {
          "type":"web_url",
          "url":"https://petersapparel.parseapp.com",
          "title":"Show Website"
        },
        {
          "type":"postback",
          "title":"Start Chatting",
          "payload":"USER_DEFINED_PAYLOAD"
        }
      ]
    }
  }
}
```

Using the Send API component makes you life easy, have a look on how the help
method use it:
```php
  /**
   * A Facebook only text.
   *
   * Facebook allows to send only 3 buttons - this what we will do.
   */
  public function facebookListOfScopes() {
    $send_api = Nuntius::facebookSendApi();

    return $send_api->templates->button
      ->text('hey there! This is the default help response ' .
      'You can try this one and override it later on. ' .
      'Hope you will get some ideas :)')
      ->addButton($send_api->buttons->postBack->title('Say something nice')->payload('something_nice'))
      ->addButton($send_api->buttons->postBack->title("What's my name?")->payload('what_is_my_name'))
      ->addButton($send_api->buttons->postBack->title('Toss a coin?')->payload('toss_a_coin'));
  }
```