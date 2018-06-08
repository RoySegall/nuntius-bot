<?php

namespace Nuntius\CapsuleTestMain\Plugin\Entity;

use Nuntius\System\EntityBase;
use Nuntius\System\Annotations\Entity as Entity;

/**
 * @Entity(
 *  id = "tag",
 *  properties = {
 *   "id",
 *   "name",
 *   "description",
 *   "vocabulary",
 *  },
 *  relations = {
 *   vocabulary = {
 *    "type" = "single",
 *    "id" = "vocabulary"
 *   }
 *  },
 * )
 */
class Tag extends EntityBase {

}
