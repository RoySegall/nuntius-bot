<?php

namespace Nuntius\Dispatcher;

/**
 * Interface for implementing event upon opening an issue/PR on GitHub.
 */
interface GitHubOpenedEventInterface {

  /**
   * When a PR or an issue opens on GitHub this method will be invoked.
   *
   * @param GitHubEvent $event
   *   The event object with information about the PR/issue.
   */
  public function act(GitHubEvent $event);

}
