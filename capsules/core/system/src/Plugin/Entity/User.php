<?php

namespace Nuntius\System\Plugin\Entity;

use Nuntius\System\EntityBase;
use Nuntius\System\Annotations\Entity as Entity;

/**
 * @Entity(
 *  id = "user",
 *  properties = {
 *   "id",
 *   "name",
 *   "password",
 *   "email"
 *  }
 * )
 */
class User extends EntityBase {

}
