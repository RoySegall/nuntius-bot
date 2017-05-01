<?php

namespace Nuntius;

/**
 * Interfaces for cron tasks.
 */
interface CronTaskInterface {

  /**
   * Running the command.
   */
  public function run();

  /**
   * Set the period.
   *
   * @param string $period
   *   The period of the operation.
   *
   * @return $this
   *   The current instance.
   */
  public function setPeriod($period);

  /**
   * Get the period.
   *
   * @return string
   *   The period of the cron task.
   */
  public function getPeriod();

  /**
   * Get the name.
   *
   * @return string
   *   The name of the task.
   */
  public function getName();

}
