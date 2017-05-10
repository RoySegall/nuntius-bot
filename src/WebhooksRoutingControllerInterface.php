<?php

namespace Nuntius;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface for webhooks routing.
 */
interface WebhooksRoutingControllerInterface {

  /**
   * Implementing a logic for the webhook incoming request.
   *
   * @param Request $request
   *   The request object.
   *
   * @return Response
   *   Return a response object. Preferable a JsonResponse.
   */
  public function response(Request $request);

}
