<?php

namespace Nuntius;

/**
 * Cron task manager.
 */
class CronManager {

  /**
   * List of cron tasks.
   *
   * @var CronTaskInterface[]
   */
  protected $cronTasks;

  /**
   * CronManager constructor.
   *
   * @param \Nuntius\NuntiusConfig $config
   */
  function __construct(NuntiusConfig $config) {
    $this->setCronTasks($config->getSetting('cron'));
  }

  /**
   * Set cron tasks.
   *
   * @param array $cron_tasks
   *   List of cron tasks.
   *
   * @return $this
   *   The current instance.
   */
  public function setCronTasks(array $cron_tasks) {

    foreach ($cron_tasks as $cron => $namespace) {
      $this->cronTasks[$cron] = new $namespace(Nuntius::container(), $cron);
    }

    return $this;
  }

  /**
   * Get all the cron tasks.
   *
   * @return CronTaskInterface[]
   *   All the cron task objects.
   */
  public function getCronTasks() {
    return $this->cronTasks;
  }

  /**
   * Get a cron task.
   *
   * @param $task
   *   The cron task id.
   *
   * @return CronTaskInterface
   *   The cron task controller.
   */
  public function getCronTask($task) {
    return $this->cronTasks[$task];
  }

}
