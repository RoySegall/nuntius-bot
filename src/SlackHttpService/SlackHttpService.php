<?php

namespace SlackHttpService;

use SlackHttpService\Payloads\SlackHttpPayloadServiceAbstract;
use SlackHttpService\Services\SlackHttpServiceChat;
use SlackHttpService\Services\SlackHttpServiceIm;
use SlackHttpService\Services\SlackHttpServiceUsers;

/**
 * Main controller for the Slack HTTP request manager.
 */
class SlackHttpService {

  /**
   * Access token of the integration.
   *
   * @var string
   */
  protected $accessToken;

  /**
   * HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $http;

  /**
   * Constructing the service.
   */
  function __construct() {
    $this->http = new \GuzzleHttp\Client();
  }

  /**
   * Get the access token.
   *
   * @return string
   */
  public function getAccessToken() {
    return $this->accessToken;
  }

  /**
   * Set the access token.
   *
   * @param string $accessToken
   *   Set the access token.
   *
   * @return SlackHttpService
   */
  public function setAccessToken($accessToken) {
    $this->accessToken = $accessToken;

    return $this;
  }

  /**
   * Apply a GET request to slack HTTP api.
   *
   * @param string $api
   *   The api of slack - user.list, user.info etc. etc.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The response interface.
   */
  public function request($api) {
    return $this->http->request('get', 'https://slack.com/api/' . $api, ['query' => ['token' => $this->accessToken]]);
  }

  /**
   * Apply a POST request to slack HTTP api.
   *
   * @param string $api
   *   The api of slack - user.list, user.info etc. etc.
   * @param $payload
   *   The payload of the post request.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The response interface.
   */
  public function post($api, SlackHttpPayloadServiceAbstract $payload) {
    return $this->http->request('post', 'https://slack.com/api/' . $api, [
      'json' => $payload->getPayload() + ['token' => $this->accessToken],
    ]);
  }

  /**
   * Apply request to slack HTTP api.
   *
   * @param string $api
   *   The api of slack - user.list, user.info etc. etc.
   * @param array $arguments
   *   Passing arguments in the request for a end point with arguments.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The response interface.
   */
  public function requestWithArguments($api, array $arguments) {
    return $this->http->request('get', 'https://slack.com/api/' . $api, ['query' => ['token' => $this->accessToken] + $arguments]);
  }

  /**
   * Get the users api service.
   *
   * @return SlackHttpServiceUsers
   *   The user service.
   */
  public function Users() {
    return new SlackHttpServiceUsers($this);
  }

  /**
   * Get the IM service.
   *
   * @return SlackHttpServiceIm
   *   The IM service.
   */
  public function Im() {
    return new SlackHttpServiceIm($this);
  }

  /**
   * Get the chat service.
   *
   * @return SlackHttpServiceChat
   *   The chat service.
   */
  public function Chat() {
    return new SlackHttpServiceChat($this);
  }

}
