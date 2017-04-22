<?php

namespace Nuntius;

/**
 * Interface for GitHub webhooks.
 */
interface GitHubWebhooksInterface {

  /**
   * The data payload.
   *
   * @param \stdClass $data
   *   The payload data.
   *
   * @return $this
   */
  public function setData($data);

  /**
   * @return array
   */
  public function getData();

  /**
   * Implementing logic for github webhook event.
   */
  public function act();

  /**
   * Acting after the act was triggered.
   */
  public function postAct();

}