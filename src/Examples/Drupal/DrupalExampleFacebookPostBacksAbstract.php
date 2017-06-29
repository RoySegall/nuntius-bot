<?php

namespace Nuntius\Examples\Drupal;

use Nuntius\FacebookPostBackAbstract;
use Nuntius\FacebookPostBackInterface;
use Nuntius\Nuntius;

/**
 * Handling facebook bot. Just handling the post backs.
 */
abstract class DrupalExampleFacebookPostBacksAbstract extends FacebookPostBackAbstract implements FacebookPostBackInterface {

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

}
