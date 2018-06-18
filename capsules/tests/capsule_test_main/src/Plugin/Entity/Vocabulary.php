<?php

namespace Nuntius\CapsuleTestMain\Plugin\Entity;

use Nuntius\System\EntityBase;
use Nuntius\System\Annotations\Entity as Entity;

/**
 * @Entity(
 *  id = "vocabulary",
 *  properties = {
 *   "id",
 *   "name",
 *   "description",
 *  },
 * )
 */
class Vocabulary extends EntityBase {

  public $id;

  public $name;

  public $description;

}
