<?php

namespace Nuntius\Update;

use Nuntius\UpdateBaseInterface;

class Update1 implements UpdateBaseInterface {

  /**
   * Describe what the update going to do.
   *
   * @return string
   *   What the update going to do.
   */
  public function description() {
    return 'Example update';
  }

  /**
   * Running the update.
   *
   * @return string
   *   A message for what the update did.
   */
  public function update() {
    return 'You run a simple update. Nothing happens but this update will not run again.';
  }

}