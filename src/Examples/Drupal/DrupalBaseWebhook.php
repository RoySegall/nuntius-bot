<?php

namespace Nuntius\Examples\Drupal;

use Nuntius\Nuntius;
use Nuntius\WebhooksRoutingControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handling incoming webhooks from Drupal.
 *
 * This is a ready to go webhoook routing for nuntius. This webhook will post
 * message to a a room about a new node.
 */
abstract class DrupalBaseWebhook implements WebhooksRoutingControllerInterface {

  /**
   * The object with the node payload.
   *
   * @var \stdClass
   */
  protected $payload;

  /**
   * The slack room.
   *
   * @var string
   */
  protected $slackRoom;

  /**
   * The address of the node.
   *
   * @var string
   */
  protected $url;

  /**
   * {@inheritdoc}
   */
  public function response(Request $request) {
    $this->payload = json_decode($request->request->get('object'));
    $this->slackRoom = $request->request->get('slack_room');
    $token = $request->request->get('token');
    $this->url = $request->request->get('url');

    if ($token != Nuntius::getSettings()->getSetting('drupal_token')) {
      Nuntius::getEntityManager()->get('logger')->save([
        'type' => 'error',
        'message' => 'The token sent in the header, "' . $token. '", is not valid.',
      ]);
      return;
    }

    return $this->trigger();
  }

  /**
   * Trigger the event.
   *
   * @return Response
   */
  abstract protected function trigger();

}
