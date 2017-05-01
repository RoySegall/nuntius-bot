<?php

namespace Nuntius;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Abstract class for cron tasks.
 */
abstract class CronTaskAbstract implements CronTaskInterface {

  /**
   * The name of the task.
   *
   * @var string
   */
  protected $name;

  /**
   * The container service.
   *
   * @var ContainerBuilder
   */
  protected $container;

  /**
   * The period operation of the cron task.
   *
   * @var string
   */
  protected $period;

  /**
   * CronTaskAbstract constructor.
   *
   * @param ContainerBuilder $container
   *   The container object.
   * @param string $name
   *   The name of the task.
   */
  public function __construct(ContainerBuilder $container, $name) {
    $this->name = $name;
    $this->container = $container;
  }

  /**
   * {@inheritdoc}
   */
  public function setPeriod($period) {
    $this->period = $period;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPeriod() {
    return $this->period;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

}
