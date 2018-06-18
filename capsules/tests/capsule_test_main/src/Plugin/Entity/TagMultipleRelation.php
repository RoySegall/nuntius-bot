<?php

namespace Nuntius\CapsuleTestMain\Plugin\Entity;

use Nuntius\System\EntityBase;
use Nuntius\System\Annotations\Entity as Entity;

/**
 * @Entity(
 *  id = "tag_many_relation",
 *  properties = {
 *   "id",
 *   "name",
 *   "description",
 *   "vocabulary",
 *  },
 *  relations = {
 *   "vocabulary" = {
 *    "type" = \Nuntius\System\EntityBase::MANY,
 *    "id" = "vocabulary"
 *   }
 *  },
 * )
 */
class TagMultipleRelation extends EntityBase {

  public $id;

  public $name;

  public $description;

  public $vocabulary;

}
