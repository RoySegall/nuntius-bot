<?php

namespace tests;
use GuzzleHttp\Exception\ServerException;

/**
 * Testing entity.
 */
class GithubWebhooksTest extends WebhooksTestsAbstract {

  /**
   * Testing failed requests.
   */
  public function testFailRequest() {
    try {
      $this->client->post('github', [
        'json' => []
      ]);
    }
    catch (ServerException $e) {
    }

    $failed_success = $this->rethinkdb->getTable('logger')
      ->filter(\r\row('type')->eq('error'))
      ->filter(\r\row('error')->eq('There is no matching webhook controller for  webhook.'))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    // Making sure a request without payload failed.
    $this->assertNotEmpty($failed_success);

    // Try failed unknown event.
    try {
      $this->client->post('github', [
        'json' => [
          'action' => 'open',
        ]
      ]);
    }
    catch (ServerException $e) {
    }

    $failed_success = $this->rethinkdb->getTable('logger')
      ->filter(\r\row('type')->eq('error'))
      ->filter(\r\row('error')->eq('There is no matching webhook controller for open webhook.'))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    // Making sure a request without payload failed.
    $this->assertNotEmpty($failed_success);
  }

  /**
   * Testing processing for known github webhooks.
   */
  public function testKnownWebhook() {
    $this->mockOpenWebhook('pull_request');
    $this->mockOpenWebhook('issue');
  }

  /**
   * Mocking a webhook for opening a PR or an issue.
   *
   * @param $key
   *   The type: pull request or issue.
   */
  protected function mockOpenWebhook($key) {
    // Testing pull request process.
    $this->client->post('github', [
      'json' => [
        'action' => 'opened',
        $key => [
          'html_url' => 'http://google.com',
          'title' => 'foo',
          'body' => 'bar',
          'created_at' => 'today',
          'user' => [
            'avatar_url' => 'http://google.com',
            'login' => 'Major. Tom',
          ],
        ],
      ]
    ]);

    $process = $this->rethinkdb->getTable('logger')
      ->filter(\r\row('logging')->eq('opened_' . $key))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    $process = reset($process)->getArrayCopy();
    $payload = $process['payload']->getArrayCopy();

    $this->assertEquals($payload['body'], 'bar');
    $this->assertEquals($payload['created'], 'today');
    $this->assertEquals($payload['image'], 'http://google.com');
    $this->assertEquals($payload['key'], $key);
    $this->assertEquals($payload['title'], 'foo');
    $this->assertEquals($payload['url'], 'http://google.com');
    $this->assertEquals($payload['username'], 'Major. Tom');
  }

}
