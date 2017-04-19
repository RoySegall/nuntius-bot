<?php

require_once 'vendor/autoload.php';

use React\EventLoop\Factory;

// Bootstrapping.
$settings = \Nuntius\Nuntius::getSettings();
$token = $settings['access_token'];

if (empty($token)) {
  throw new Exception('The access token is missing');
}

// Set up stuff.
$client_loop = React\EventLoop\Factory::create();
$client = new Slack\RealTimeClient($client_loop);
$client->setToken($token);

// Iterating over the plugins and register them for Slack events.
foreach ($settings['events'] as $event => $namespace) {
  /** @var \Nuntius\NuntiusPluginAbstract $plugin */
  $plugin = new $namespace($client);

  $client->on($event, function ($data) use ($plugin) {
    $plugin->data = $data->jsonSerialize();

    $plugin->preAction();
    $plugin->action();
    $plugin->postAction();
  });
}

echo "a";


// Login something to screen when the bot started to work.
$client->connect()->then(function () {
  echo "Nuntius started to work at " . date('d/m/Y H:i');
});

// Starting the work.
$client_loop->run();
