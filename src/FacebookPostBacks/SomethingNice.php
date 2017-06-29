<?php

namespace Nuntius\FacebookPostBacks;

use Nuntius\FacebookPostBackAbstract;
use Nuntius\FacebookPostBackInterface;

class SomethingNice extends FacebookPostBackAbstract implements FacebookPostBackInterface {

  /**
   * {@inheritdoc}
   */
  public function postBack() {
    $texts = [
      'You look lovely!',
      'Usually you wakes up looking good. Today, you took it to the next level!',
      'Hey there POTUS... sorry! thought you are some one else...',
    ];

    shuffle($texts);
    return reset($texts);
  }

}
