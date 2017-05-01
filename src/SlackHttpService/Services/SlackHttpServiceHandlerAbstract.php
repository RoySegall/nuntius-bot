<?php

namespace SlackHttpService\Services;

use Psr\Http\Message\ResponseInterface;
use SlackHttpService\SlackHttpService;

/**
 * Most of the api have shared logic. This class will share it.
 */
abstract class SlackHttpServiceHandlerAbstract {

  /**
   * Slack HTTP service.
   *
   * @var SlackHttpService
   */
  protected $slackHttpService;

  /**
   * The main api - users, messages etc. etc.
   *
   * @var string
   */
  protected $mainApi;

  /**
   * SlackHttpServiceUsers constructor.
   *
   * @param SlackHttpService $slack_http_service
   *   Slack HTTP service.
   */
  function __construct(SlackHttpService $slack_http_service) {
    $this->slackHttpService = $slack_http_service;
  }

  /**
   * Decode a sub api request such as: user.list, users.info.
   *
   * @param $sub_api
   *   The sub api - list, info.
   *
   * @return \stdClass
   *   The JSON representation of the user list request.
   */
  protected function decodeApiRequest($sub_api) {
    return $this->decodeRequest($this->slackHttpService->request($this->mainApi . '.' . $sub_api));
  }

  /**
   * Decode a request object.
   *
   * @param ResponseInterface $request
   *   The object.
   *
   * @return \stdClass
   *   The JSON representation of the user list request.
   */
  protected function decodeRequest(ResponseInterface $request) {
    return json_decode($request->getBody()->getContents());
  }

}
