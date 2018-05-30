<?php

namespace Nuntius\System\Plugin\Entity;

use Nuntius\System\Entity;

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
