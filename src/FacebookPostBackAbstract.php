<?php

namespace Nuntius;
use FacebookMessengerSendApi\SendAPI;

/**
 * Abstract class for the FB postback manager.
 */
abstract class FacebookPostBackAbstract implements FacebookPostBackInterface {

  /**
   * The FB request object.
   *
   * @var array
   */
  protected $fbRequest;

  /**
   * The send API object.
   *
   * @var SendAPI
   */
  protected $facebookSendAPI;

  /**
   * Constructing the tasks manager.
   *
   * @param SendAPI $facebook_send_api
   *   Facebook send API.
   */
  public function __construct(SendAPI $facebook_send_api) {
    $this->facebookSendAPI = $facebook_send_api;
  }

  /**
   * {@inheritdoc}
   */
  public function setFbRequest(array $fb_request) {
    $this->fbRequest = $fb_request;

    return $this;
  }

  /**
   * Get the sender information.
   *
   * @param $id
   *   The ID of the user.
   * @param string $fields
   *   The fields we desire to retrieve. Default to first and last name. The
   *   fields separated by comma.
   *
   * @return mixed
   */
  protected function getSenderInfo($id, $fields = 'first_name,last_name') {
    return json_decode(Nuntius::getGuzzle()->get('https://graph.facebook.com/v2.6/' . $id, [
      'query' => [
        'access_token' => Nuntius::getSettings()->getSetting('fb_token'),
        'fields' => $fields,
      ],
    ])->getBody());
  }

}
