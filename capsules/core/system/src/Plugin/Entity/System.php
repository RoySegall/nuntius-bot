<?php

namespace Nuntius\System\Plugin\Entity;

use Nuntius\System\EntityBase;
use Nuntius\System\Annotations\Entity as Entity;

/**
 * @Entity(
 *  id = "system",
 *  properties = {
 *   "id",
 *   "name",
 *   "description",
 *   "machine_name",
 *   "path",
 *   "status",
 *   "time",
 *   "weight",
 *  },
 *  constraints = {
 *   "machine_name" = {
 *    {"\Nuntius\System\Constraints\EntityConstraints", "capsuleNameUnique"},
 *    {"\Nuntius\System\Constraints\EntityConstraints", "capsuleExists"},
 *   },
 *   "path" = {
 *    {"\Nuntius\System\Constraints\EntityConstraints", "pathExists"},
 *   },
 *  },
 * )
 */
class System extends EntityBase {

}
