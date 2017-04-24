<?php

require_once 'vendor/autoload.php';

$client = \Nuntius\Nuntius::bootstrap();

$_POST['payload'] = empty($_POST['payload']) ? file_get_contents("php://input") : $_POST['payload'];

if (empty($_POST['payload'])) {
  \Nuntius\Nuntius::getEntityManager()->get('logger')->insert([
    'type' => 'error',
    'error' => 'No payload found in the post.',
  ]);
  return;
}

$payload = json_decode($_POST['payload']);
$event = $payload->action;

if (!$namespace = \Nuntius\Nuntius::getSettings()->getSetting('webhooks')['github'][$event]) {
  \Nuntius\Nuntius::getEntityManager()->get('logger')->insert([
    'type' => 'error',
    'error' => 'There is no matching webhook controller for ' . $event . ' webhook.',
  ]);
  return;
}

/** @var \Nuntius\GitHubWebhooksAbstract $webhook */
$webhook = new $namespace;

// Acting.
$webhook
  ->setData($payload)
  ->act();

// Post acting.
$webhook->postAct();
