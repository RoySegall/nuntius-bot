<?php

namespace Nuntius\Db\RethinkDB;

use Nuntius\Db\DbMetadataHandlerInterface;

/**
 * RethinkDB metadata handler.
 */
class RethinkDbMetadataHandler implements DbMetadataHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function dbType() {
    return 'NoSQL';
  }

  /**
   * {@inheritdoc}
   */
  public function installerDescription() {
    return 'No SQL light weight DB with real time support.';
  }

  /**
   * {@inheritdoc}
   */
  public function supportRealTime() {
    return TRUE;
  }
}
