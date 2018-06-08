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
 *  }
 * )
 */
use Nuntius\System\EntityBase;
use Nuntius\System\Annotations\Entity as Entity;
use Symfony\Component\Validator\Constraints as Assert;

class System extends EntityBase {

  public $id;

  public $name;

  public $description;

  public $machine_name;

  public $path;

  public $status;

  public $time;

  public $weight;

  /**
   * {@inheritdoc}
   */
  protected function constraints() {
    return [
      'name' => [
        new Assert\NotBlank(),
      ],
      'description' => [
        new Assert\NotBlank(),
      ],
      'machine_name' => [
        new Assert\NotBlank(),
      ],
      'path' => [
        new Assert\NotBlank(),
        new \Nuntius\System\Constraints\PathExists(),
      ],
      'status' => [
        new Assert\NotBlank(),
      ],
    ];
  }
}
