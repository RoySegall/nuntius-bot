# Nuntius slack bot
Gizra became a company when a lot of the employees are remote: USA, 
Canada, Spain and the list goes on. That status required from us to start using 
Slack. But the problem is that we wanted Slack to be cool. The obvious idea is 
to have a bot. The bot will interact with us and might improve the way we 
communicates.

## Origin
Like any awesome super hero, Nuntius have an origin story. It's not a tragic 
origin story when his uncle-CPU died due to lack of understanding that with 
great power comes great responsibility.

Nuntius in Latin means messages. That was the original project - a chat based on
any backend technology: Drupal, Wordpress, NodeJS, etc., etc. that could connect
to any front end technology(React, Elm, Angular, etc., etc.) and using any 
websocket service(Socket.IO, Pusher, FireBase). The project was too much for a 
single man but the name lived on.

## Set up.
You'll need PHP 5.6 and above, [Composer](http://getcomposer.org) and 
[RethinkDB](http://rethinkdb.com).

Go to [slack custom integration](https://gizrateam.slack.com/apps/A0F7YS25R-bots)
to get the bot access token(it will change any day so keep that in mind when 
updating Nuntius).

```bash
cp settings.local.example.yml settings.local.yml
composer install
rethinkdb
```

Open the settings file you created and set the token you copied, and run:
```bash
php console.php nuntius:install
php bot.php
```

That's it. Nuntius is up and running.
