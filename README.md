`[![Build Status](https://travis-ci.org/RoySegall/gizra_slack_bot.svg?branch=master)](https://travis-ci.org/RoySegall/gizra_slack_bot)
`
## Nuntius slack bot
Gizra became a company when a lot of the employees are actually remote: USA, 
Canada, Spain and the list goes on. That status required from us to start using 
Slack. But the problem is that we wanted slack to be cool. The obvious idea is 
to have a bot. The bot will interact with us and might improve the way we 
communicates.

## Origin
Like any awesome super hero Nuntius have an origin story. This is not a tragic 
origin story when the his uncle-CPU died due to lack of understanding that with 
with great power comes great responsibility.

Nuntius in Latin means messages and that was the original project - a chat based
on any backend technology(Drupal, Wordpress, NodeJS etc. etc.) that could 
connect to any front end technology(React, Elm, Angular etc. etc.) and using any 
websocket service(Socket.IO, Pusher, FireBase). The project was to much for a 
single man but the name lived on.

## Set up.
You'll need PHP5.6 and above, [Composer](http://getcomposer.org) and 
[RethinkDB](http://rethinkdb.com).

Go to [slack custom integration](https://gizrateam.slack.com/apps/A0F7YS25R-bots)
to get the bot access token(it will change any day so keep that in mind when 
updating nuntius).

```bash
cp settings.example.yml settings.yml
composer install
rethinkdb
```

Open the settings file you created and set the token you copied. and run:
```bash
php install.php
php bot.php
```

That's it. Nuntius is up and running.

## Adding reactions
The main idea of nuntius is reacting with people. Each reaction belong to a 
category. A category is bundled into a class which located in the `src/Plugins`
directory.

Have a look:
```php

class Reminder extends NuntiusPluginAbstract {

  /**
   * @inheritdoc
   */
  protected $category = 'Reminders';

  public $formats = [
    '/Command with argument (.*) that will be passed as an argument/' => [
      'callback' => 'CommandWithArguments',
      'human_command' => 'Command with argument ARGUMENT that will be passed as an argument',
      'description' => 'This is description',
    ],
    'known text 1,known text 2' => [
      'callback' => 'KnownText',
      'human_command' => 'when USERNAME is logged in tell him STUFF',
      'description' => 'known text 1,known text 2',
    ],
  ];

  /**
   * Adding a reminder for some one.
   *
   * @param $to
   *   The user we need to remind.
   * @param $remind
   *   The reminder.
   *
   * @return string
   *   The text to return after the action was done.
   */
  public function RemindTo($to, $remind) {
    Nuntius::getRethinkDB()->addEntry('table', [
      'key' => 'value',
    ]);

    return "Don't worry! I got your back. I'll send him a reminder.";
  }

}


```
