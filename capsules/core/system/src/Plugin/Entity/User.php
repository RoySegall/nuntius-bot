<?php

namespace Nuntius\System\Plugin\Entity;

use Nuntius\System\Annotations\Entity;

/**
 * @Entity(
 *  id = "user",
 *  properties = {
 *   "id",
 *   "name",
 *   "password",
 *   "email"
 *  },
 * )
 */
class User extends Entity {

}
