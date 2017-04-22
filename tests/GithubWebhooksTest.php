<?php

namespace tests;

/**
 * Testing entity.
 */
class GithubWebhooksTest extends GithubWebhooksTestsAbstract {

  /**
   * Testing failed requests.
   */
  public function testFailRequest() {
    $this->client->post('github.php', [
      'json' => []
    ]);

    $failed_success = $this->rethinkdb->getTable('logger')
      ->filter(\r\row('type')->eq('error'))
      ->filter(\r\row('error')->eq('There is no matching webhook controller for  webhook.'))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    // Making sure a request without payload failed.
    $this->assertNotEmpty($failed_success);

    // Try failed unknown event.
    $this->client->post('github.php', [
      'json' => [
        'action' => 'open',
      ]
    ]);

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
    $this->client->post('github.php', [
      'json' => [
        'action' => 'opened',
        $key => [
          'title' => 'foo',
          'body' => 'bar',
          'user' => [
            'login' => 'Major. Tom',
          ]
        ],
      ]
    ]);

    $process = $this->rethinkdb->getTable('logger')
      ->filter(\r\row('logging')->eq('opened_pull_request'))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    $process = reset($process)->getArrayCopy();
    $payload = $process['payload']->getArrayCopy();

    $this->assertEquals($payload['title'], 'foo');
    $this->assertEquals($payload['user'], 'Major. Tom');
  }

}
