<?php

namespace tests;
use GuzzleHttp\Exception\ServerException;

/**
 * Testing Facebook intergration.
 */
class FacebookWebhooksTest extends WebhooksTestsAbstract {

  /**
   * Testing facebook intergration. As much as we can.
   */
  public function testValidationFromFacebook() {
    try {
      $this->client->post('facebook', [
        'json' => []
      ]);
    }
    catch (ServerException $e) {
    }

    try {
      $response = $this->client->post('facebook?hub_challenge=123', [
        'json' => []
      ]);
    }
    catch (ServerException $e) {
    }

    $this->assertEquals(123, $response->getBody()->getContents());
  }

  /**
   * Testing the texts handeling.
   */
  public function testTexts() {
//    $this->client->post('facebook', [
//      'json' => []
//    ]);
  }

}
