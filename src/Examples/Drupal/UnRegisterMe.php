<?php

namespace Nuntius\Examples\Drupal;

use Nuntius\Nuntius;

class UnRegisterMe extends DrupalExampleFacebookPostBacksAbstract {

  /**
   * {@inheritdoc}
   */
  public function postBack() {
    $row = $this->queryForRecipient($this->fbRequest['sender']);

    Nuntius::getEntityManager()->get('fb_reminders')->delete($row[0]['id']);

    return "You don't want to get updates. That's OK. See you in the future";
  }

}
