<?php

namespace Nuntius\System\Plugin\Entity;

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
 *   "id" = {
 *    {"\Nuntius\System\Constraints\EntityConstraints", "isUnique"},
 *   },
 *   "name" = {
 *    {"\Nuntius\System\Constraints\EntityConstraints", "required"},
 *   },
 *   "machine_name" = {
 *    {"\Nuntius\System\Constraints\EntityConstraints", "required"},
 *   },
 *   "path" = {
 *    {"\Nuntius\System\Constraints\EntityConstraints", "pathExists"},
 *   },
 *   "status" = {
 *    {"\Nuntius\System\Constraints\EntityConstraints", "isStatusValid"},
 *   },
 *   "time" = {
 *    {"\Nuntius\System\Constraints\EntityConstraints", "isInt"},
 *   },
 *   "weight" = {
 *    {"\Nuntius\System\Constraints\EntityConstraints", "isInt"},
 *   },
 *  },
 * )
 */
use Nuntius\System\EntityBase;
use Nuntius\System\Annotations\Entity as Entity;

class System extends EntityBase {

}
