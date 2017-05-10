<?php

namespace Nuntius\WebhooksRounting;

use Nuntius\WebhooksRoutingControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handling incoming webhooks from GitHub.
 */
class GitHub implements WebhooksRoutingControllerInterface {

  /**
   * {@inheritdoc}
   */
  public function response(Request $request) {

    $payload = empty($_POST['payload']) ? file_get_contents("php://input") : $_POST['payload'];

    if (empty($payload)) {
      $data = [
        'type' => 'error',
        'error' => 'No payload found in the post.',
      ];
      \Nuntius\Nuntius::getEntityManager()->get('logger')->insert($data);

      return new JsonResponse($data, 501);
    }

    $payload = json_decode($payload);
    $event = $payload->action;

    if (!$namespace = \Nuntius\Nuntius::getSettings()->getSetting('webhooks')['github'][$event]) {
      $data = [
        'type' => 'error',
        'error' => 'There is no matching webhook controller for ' . $event . ' webhook.',
      ];
      \Nuntius\Nuntius::getEntityManager()->get('logger')->insert($data);

      return new JsonResponse($data, 501);
    }

    /** @var \Nuntius\GitHubWebhooksAbstract $webhook */
    $webhook = new $namespace;

    // Acting.
    $webhook
      ->setData($payload)
      ->act();

    // Post acting.
    $webhook->postAct();

    return new JsonResponse(['type' => 'success', 'message' => 'The request has been processed.']);
  }

}
