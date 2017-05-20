<?php

namespace Nuntius;

use Slack\RealTimeClient;

/**
 * Interface for tasks.
 */
interface TaskBaseInterface {

  /**
   * Return information about the scope of the task.
   *
   * @return mixed
   *   Information about the scope.
   */
  public function scope();

  /**
   * Acting when the user logged in or out.
   *
   * @todo: move to a dispatch event system.
   */
  public function actOnPresenceChange();

  /**
   * Set the client object.
   *
   * @param \Slack\RealTimeClient $client
   *   The client object.
   *
   * @return $this
   *   The current instance.
   */
  public function setClient(RealTimeClient $client);

  /**
   * Set the data form the RTM event.
   *
   * @param array $data
   *   The data of the RTM event.
   *
   * @return $this
   *   The current instance.
   */
  public function setData(array $data);

  /**
   * Get the task ID.
   *
   * @return string
   *   The task ID.
   */
  public function getTaskId();

}
