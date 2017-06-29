<?php

namespace Nuntius\FacebookPostBacks;

use Nuntius\FacebookPostBackAbstract;
use Nuntius\FacebookPostBackInterface;

class WhatIsMyName extends FacebookPostBackAbstract implements FacebookPostBackInterface {

  /**
   * {@inheritdoc}
   */
  public function postBack() {
    $info = $this->getSenderInfo($this->fbRequest['sender']);
    return 'You are ' . $info->first_name . ' ' . $info->last_name . ', in case you forgot';
  }

}
