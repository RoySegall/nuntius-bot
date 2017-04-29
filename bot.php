<?php

use Nuntius\Nuntius;

require_once 'vendor/autoload.php';

// Bootstrapping.
$client = Nuntius::bootstrap();
$settings = Nuntius::getSettings();

// Iterating over the plugins and register them for Slack events.
foreach ($settings->getSetting('events') as $event => $namespace) {
  /** @var \Nuntius\NuntiusPluginAbstract $plugin */
  $plugin = new $namespace($client);

  $client->on($event, function ($data) use ($plugin) {
    $plugin->data = $data->jsonSerialize();

    $plugin->preAction();
    $plugin->action();
    $plugin->postAction();
  });
}

// Login something to screen when the bot started to work.
$client->connect()->then(function () {
  echo "Nuntius started to work at " . date('d/m/Y H:i');
});

// Starting the work.
Nuntius::run();
