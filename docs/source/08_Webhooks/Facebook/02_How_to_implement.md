You set up as you saw in 
[Configure the integration](Webhooks/Facebook/Configure_the_integration.html).

If you'll write something like `Hey` or `Hello` you'll get something like that:

![Facebook ste 3](../../images/facebook/engage-1.png)

But if you'll write `help` you'll get something like that:

![Facebook ste 3](../../images/facebook/engage-2.png)

You wou'ld probably want to override that. It's very easy, just override the 
class by update your `hooks.local.yml`:
```yaml
webhooks_routing:
  'facebook': '\tests\overrides\NuntiusFacebookOverride'
```

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