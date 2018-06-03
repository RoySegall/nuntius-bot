<?php

namespace Nuntius\CapsuleTestMain\Plugin\Entity;

use Nuntius\System\EntityBase;
use Nuntius\System\Annotations\Entity as Entity;

/**
 * @Entity(
 *  id = "tags",
 *  properties = {
 *   "id",
 *   "name",
 *   "password",
 *   "email"
 *  },
 * )
 */
class Tags extends EntityBase {

}