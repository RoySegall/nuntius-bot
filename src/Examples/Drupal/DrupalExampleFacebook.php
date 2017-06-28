<?php

namespace Nuntius\Examples\Drupal;

use Nuntius\Nuntius;
use Nuntius\WebhooksRounting\Facebook;

/**
 * Handling facebook bot. Just handling the post backs.
 */
class DrupalExampleFacebook extends Facebook {

  /**
   * Return an answer according to the postback button.
   *
   * @return string
   *   The string to return to the user.
   */
  protected function helpRouter() {
    switch ($this->fbRequest['postback']) {
      case 'register_me':
        $this->saveRecipientId($this->fbRequest['sender']);
        return 'Got it! You will be notified on new stuff';

      case 'un_register_me':
        $this->deleteRecipient($this->fbRequest['sender']);
        return "You don't want to get updates. That's OK. See you in the future";
    }
  }

  /**
   * Check if the recipient ID is already registered.
   *
   * @param $id
   *   The ID.
   *
   * @return array
   */
  protected function queryForRecipient($id) {
    return Nuntius::getDb()->getQuery()
      ->table('fb_reminders')
      ->condition('recipient_id', $id)
      ->execute();
  }

  /**
   * Save the recipient ID.
   *
   * @param $id
   *   The recipient ID of the user.
   */
  protected function saveRecipientId($id) {
    // Check that the recipient is already registered.
    if ($this->queryForRecipient($id)) {
      return;
    }

    Nuntius::getEntityManager()->get('fb_reminders')->save(['recipient_id' => $this->fbRequest['sender']]);
  }

  /**
   * Delete the recipient ID.
   *
   * @param $id
   *   The ID.
   */
  protected function deleteRecipient($id) {
    $row = $this->queryForRecipient($id);

    Nuntius::getEntityManager()->get('fb_reminders')->delete($row[0]['id']);
  }

}
