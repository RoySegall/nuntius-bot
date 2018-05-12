<?php

namespace Nuntius\Db\MongoDB;

use Nuntius\Db\DbMetadataHandlerInterface;

/**
 * MongoDB metadata handler.
 */
class MongoDBbMetadataHandler implements DbMetadataHandlerInterface {

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
    return 'No SQL light weight DB';
  }

  /**
   * {@inheritdoc}
   */
  public function supportRealTime() {
    return FALSE;
  }

}
