[![Build Status](https://travis-ci.org/RoySegall/nuntius-bot.svg?branch=master)](https://travis-ci.org/RoySegall/nuntius-bot)

# Nuntius slack bot
Gizra became a company when a lot of the employees are remote: USA, 
Canada, Spain and the list goes on. That status required from us to start using 
Slack. But the problem is that we wanted Slack to be cool. The obvious idea is 
to have a bot. The bot will interact with us and might improve the way we 
communicate.

## Origin
Like any awesome superhero, Nuntius have an origin story. It's not a tragic 
origin story when his uncle-CPU died due to lack of understanding that with 
great power comes great responsibility.

Nuntius in Latin means messages. That was the original project - a chat based on
any backend technology: Drupal, Wordpress, NodeJS, etc., etc. that could connect
to any front end technology(React, Elm, Angular, etc., etc.) and using any 
WebSocket service(Socket.IO, Pusher, FireBase). The project was too much for a 
single man but the name lived on.

## Set up.
You'll need PHP 5.6 and above, [Composer](http://getcomposer.org) and 
[RethinkDB](http://rethinkdb.com).

After creating a bot, Go to `https://YOURTEAM.slack.com/apps`. Click on `Manage`
and under `Custom integration` you'll see your bot. Click on the bot to get the
access token.

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

## Integrating
Integration can be done through the main `settings.yml` file(or in case you
forked the project through the `settings.local.yml`). The `settings.local.yml`
file will override the settings of the `settings.yml` file.

Let's go through the various integrations.

## Events
Integration with slack can be achieved in various ways. Nuntius implementing the
integration via WebSocket and push events AKA RTM events. For any operation on
slack, there is a matching RTM event. You can look on the list 
[here](https://api.slack.com/rtm#events).

Let's see how to interact with the message events. In the `settings.yml` we have
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

## Entities
At some point you might want to keep stuff in the DB. The database is based on 
Rethinkdb. Similar to event integration definition, entity defined in the 
`settings.yml` file:
```yml
entities:
  reminders: '\Nuntius\Entity\Reminders'
  context: '\Nuntius\Entity\Context'
  context_archive: '\Nuntius\Entity\RunningContext'
  running_context: '\Nuntius\Entity\RunningContext'
  system: '\Nuntius\Entity\System'
```

You could implement methods relate to the entity in the matching class but you
will see that the basic methods are enough.

### Add an entry

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->insert(['foo' => 'bar']);
```

### Load an entry

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->load(ID);
```

### Load all the entries

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->loadAll();
```

### Update an entry

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->update(ID, ['foo' => 'bar']);
```

### Delete an entry

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->delete(ID);
```

### Query in the DB
Except for the CRUD layer, sometimes you need to look for items. Have a look at
the code:
```php
<?php
  \Nuntius\Nuntius::getRethinkDB()
    ->getTable('running_context')
    ->filter(\r\row('foo')->eq('bar'))
    ->filter(\r\row('bar')->ne('fo'))
    ->run($this->db->getConnection())
    ->toArray();
```

## Tasks
One way to communicate with Nuntius is via text. First, let's have a look at the
`settings.yml` file:
```yml
tasks:
  reminders: '\Nuntius\Tasks\Reminders'
  help: '\Nuntius\Tasks\Help'
  introduction: '\Nuntius\Tasks\Introduction'
```

The tasks plugin needs to declare to which text it needs to response AKA scope:

There two types of plugins:
1. Black box task - A task that needs arguments, or not, and does a simple job: 
set a reminder for later.
2. Conversation task - A task which depends on information and can get it by
asking the user a couple of questions. Each conversation task has a 
conversation scope:
  * Forever - a scope that likely won't change in the near future: List of the
  user's team members.
  * Temporary - A scope that we don't need to keep forever: What you want to eat
  for lunch. **But** a temporary scope may not be relevant forever but we might
  want to use in the future. We would likely want to keep the places the user
  invited food from so we could suggest that in the past.

Let's dive into the code.

### Black box task
```php
<?php

namespace Nuntius\Tasks;

/**
 * Remind to the user something to do.
 */
class Reminders extends TaskBaseAbstract implements TaskBaseInterface {

  /**
   * {@inheritdoc}
   */
  public function scope() {
    return [
      '/remind me (.*)/' => [
        'human_command' => 'remind me REMINDER',
        'description' => 'Next time you log in I will remind you what you '
        . ' wrote in the REMINDER',
        'callback' => 'addReminder',
      ],
    ];
  }

  /**
   * Adding a reminder to the DB.
   *
   * @param string $reminder
   *   The reminder of the user.
   *
   * @return string
   *   You got it dude!
   */
  public function addReminder($reminder) {
    $this->entityManager->get('reminders')->insert([
      'reminder' => $reminder,
      'user' => $this->data['user'],
    ]);

    return 'OK! I got you covered!';
  }

}
```
In the `method` scope we define to which text we need to respond. Each `(.*)` is
an argument. The keys meaning are:
  * `human_command`: An example of how user input should be.
  * `description`: Describing what the command will do.
  * `callback`: The callback which will be invoked with the argument you expect
  to receive.
  
### Conversation task
Let's look first at the code and explain how to write the plugin:
```php
<?php

namespace Nuntius\Tasks;


/**
 * Remind to the user something to do.
 */
class Introduction extends TaskConversationAbstract implements TaskConversationInterface {

  /**
   * {@inheritdoc}
   */
  public function scope() {
    return [
      '/nice to meet you/' => [
        'human_command' => 'nice to meet you',
        'description' => 'We will do a proper introduction',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function conversationScope() {
    return 'forever';
  }

  /**
   * Get the user first name.
   */
  public function questionFirstName() {
    return 'Oh hey! It looks that we are not introduced yet. what is your first name?';
  }

  /**
   * Get the last name of the user.
   */
  public function questionLastName() {
    return 'what is your last name?';
  }

  /**
   * {@inheritdoc}
   */
  public function collectAllAnswers() {
    return 'Well, ' . $this->answers['FirstName'] . ' ' . $this->answers['LastName'] . ', it is a pleasure.';
  }

}

```

First, it's important to implement the `TaskConversationInterface` interface. 
This is the way we recognize this a conversation task.

Similar to the black box task we do define a scope but in this case, we don't
define a callback. That's because nuntius will ask the question by a naming
conventions method: methods with a `question` prefix will be invoked(similar to 
Unit test). The method needs to return the text of the question. The questions 
will be triggered by the order in the class - so keep in a rational order of 
methods.

When nuntius collected all the answers, the `collectAllAnswers` will be invoked.
The answers will be available in the `answers` property with the matching name
of the method which holds the question but without the `question` prefix.

In case something got in the way and the user lost his internet connection or
the server went down the answers won't get lost. The answers stored in the DB
except for a temporary context conversation. The answers will move into an
archive and won't be available for next time the conversation will start.

## Updates
You deployed nuntius and you added some functionality but that functionality
needs some new entities tables, maybe change some information about the user 
etc. etc. For that case, we have the updates mechanism.

Implementing a new update:
```yml
updates:
  1: '\Nuntius\Update\Update1'
```

The code look pretty obvious:
```php
<?php

namespace Nuntius\Update;

class Update1 implements UpdateBaseInterface {

  /**
   * Describe what the update going to do.
   *
   * @return string
   *   What the update going to do.
   */
  public function description() {
    return 'Example update';
  }

  /**
   * Running the update.
   *
   * @return string
   *   A message for what the update did.
   */
  public function update() {
    return 'You run a simple update. Nothing happens but this update will not run again.';
  }

}
```

About the methods:
* `description`: explain what the update is going to do.
* `update`: Preform the update. The text the function will return will show 
after the update was invoked successfully.

A couple of rules:
1. Updates that invoked before won't invoked again.
2. The update will be invoked in the order in the yml file.
3. When installing nuntius, all the listed updates will be marked as updates 
which invoked already.

## Commands

Commands are an easy way to add CLI integration. The commands based on the
Symfony console component so we won't go and explain the API. You can read about
it [here](https://symfony.com/doc/current/console.html)

Let's have a look on how to define:
```yml
commands:
  - '\Nuntius\Commands\UpdateCommand'
  - '\Nuntius\Commands\InstallCommand'
```

Let's have a look at the code that installs Nuntius for us:
```php
<?php

namespace Nuntius\Commands;

class InstallCommand extends Command  {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('nuntius:install')
      ->setDescription('Install nuntius')
      ->setHelp('Set up nuntius');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {

    $io = new SymfonyStyle($input, $output);

    $value = Nuntius::getSettings();
    $db = Nuntius::getRethinkDB();

    $io->section("Setting up the DB.");

    $db->createDB($value['rethinkdb']['db']);
    $io->success("The DB was created");

    sleep(5);

    $io->section("Creating entities tables.");

    foreach (array_keys($value['entities']) as $scheme) {
      $db->createTable($scheme);
      $io->success("The table {$scheme} has created");
    }

    // Run this again.
    $db->getTable('system')->insert(['id' => 'updates', 'processed' => []])->run($db->getConnection());

    Nuntius::getEntityManager()->get('system')->update('updates', ['processed' => array_keys(Nuntius::getUpdateManager()->getUpdates())]);

    $io->section("The install has completed.");
    $io->text('run php bot.php');
  }

}
```

## Webhooks integration - Github
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

## Uncovered API to this point
We covered a lot of the integration you can have with Nuntius and slack but
let's look at some code snippets:

### The managers

Get the settings:
```php
<?php
\Nuntius\Nuntius::getSettings();
```

Get the DB layer:
```php
<?php
\Nuntius\Nuntius::getRethinkDB();
```

Get the entity manager:
```php
<?php
\Nuntius\Nuntius::getEntityManager();
```

Get the task manager:
```php
<?php
\Nuntius\Nuntius::getTasksManager();
```

Get the update manager:
```php
<?php
\Nuntius\Nuntius::getUpdateManager();
```

### Slack how to

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

## Tests

In order to test GitHub webhooks set the local env variable `NUNTIUS_BASE_URL`
to the address of your nuntius installation:

```bash
export NUNTIUS_BASE_URL=http://localhost:8888
```

After that fire up the test server:
```php
php -S localhost:8888
```

Running tests is easy:

```bash
bash tests.sh
```
