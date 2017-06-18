<?php

namespace tests;

use GuzzleHttp\Exception\ServerException;
use Nuntius\Nuntius;

/**
 * Testing Facebook intergration.
 */
class FacebookWebooksTest extends WebhooksTestsAbstract {

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
      $response = $e->getMessage();
      $this->assertContains('Fatal error', $response);
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
    $json = [
      'entry' => [
        0 => [
          'id' => 10,
          'time' => time(),
          'messaging' => [
            0 => [
              'sender' => ['id' => 20],
              'recipient' => ['id' => 30],
              'message' => [
                'text' => 'Dummy text',
                'mid' => '5e34bbca',
                'seq' => 'foo',
              ],
            ],
          ],
        ],
      ],
    ];

    $this->client->post('facebook', [
      'json' => $json,
    ]);

    $results = Nuntius::getDb()->getQuery()
      ->table('logger')
      ->condition('text', "Hmm.... Sorry, I can't find something to tell you. Try something else, mate.")
      ->execute();

    $this->assertNotEmpty($results);

    // Send a real facebook text task.
    $json['entry'][0]['messaging'][0]['message']['text'] = 'help';

    $this->client->post('facebook', [
      'json' => $json,
    ]);

    $results = Nuntius::getDb()->getQuery()
      ->table('logger')
      ->condition('attachment', '', '!=')
      ->execute();

    $this->assertEquals($results[0]['attachment']['payload']['text'], "hey there! This is the default help response You can try this one and override it later on. Hope you will get some ideas :)");

    // Triggering a post back.
    unset($json['entry'][0]['messaging'][0]['message']['text']);
    $json['entry'][0]['messaging'][0]['postback']['payload'] = 'toss_a_coin';
    $this->client->post('facebook', [
      'json' => $json,
    ]);

    $results = Nuntius::getDb()->getQuery()
      ->table('logger')
      ->condition('text', ["Tossing.... it's heads", "Tossing.... it's tail"], 'IN')
      ->execute();

    $this->assertNotEmpty($results);
  }

}
