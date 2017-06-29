<?php

namespace Nuntius\FacebookPostBacks;

use Nuntius\FacebookPostBackAbstract;
use Nuntius\FacebookPostBackInterface;

class TossACoin extends FacebookPostBackAbstract implements FacebookPostBackInterface {

  /**
   * {@inheritdoc}
   */
  public function postBack() {
    $options = ['heads', 'tail'];
    shuffle($options);
    $result = reset($options);

    return "Tossing.... it's " . $result;
  }

}
