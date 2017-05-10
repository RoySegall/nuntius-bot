<?php

namespace tests;

use GuzzleHttp\Client;

/**
 * Abstract classes for web hooks tests.
 */
abstract class WebhooksTestsAbstract extends TestsAbstract {

  /**
   * The guzzle object wrapping the base url for testing.
   *
   * @var Client
   */
  protected $client;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->client = new Client(['base_uri' => getenv('NUNTIUS_BASE_URL')]);
  }

}
