<?php

namespace Nuntius\Examples\Drupal;

use Nuntius\Nuntius;

class RegisterMe extends DrupalExampleFacebookPostBacksAbstract {

  /**
   * {@inheritdoc}
   */
  public function postBack() {
    // Check that the recipient is already registered.
    if ($this->queryForRecipient($this->fbRequest['sender'])) {
      return;
    }

    Nuntius::getEntityManager()->get('fb_reminders')->save(['recipient_id' => $this->fbRequest['sender']]);
    return 'Got it! You will be notified on new stuff';
  }

}
