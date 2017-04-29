<?php

namespace Nuntius\Cron;

use Nuntius\CronTaskAbstract;
use Nuntius\CronTaskInterface;

class LogThings extends CronTaskAbstract implements CronTaskInterface {

  /**
   * {@inheritdoc}
   */
  protected $period = '*/1 * * * *';

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->container->get('manager.entity')
      ->get('logger')
      ->insert([
        'inside' => 'yes',
        'time' => date('d/m/Y H:i', time()),
      ]);
  }

}
