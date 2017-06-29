<?php

namespace Nuntius\Examples\Drupal;

use Nuntius\Nuntius;
use Nuntius\UpdateBaseAbstract;
use Nuntius\UpdateBaseInterface;

/**
 * Adding the FB reminders entity.
 */
class AddFbReminder extends UpdateBaseAbstract implements UpdateBaseInterface {

  /**
   * Describe what the update going to do.
   *
   * @return string
   *   What the update going to do.
   */
  public function description() {
    return 'Adding the FB reminders entity';
  }

  /**
   * Running the update.
   *
   * @return string
   *   A message for what the update did.
   */
  public function update() {
    Nuntius::getDb()->getOperations()->tableCreate('fb_reminders');
    return 'The FB reminders table was added.';
  }

}
