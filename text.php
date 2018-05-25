<?php

require_once 'autoloader.php';

$entity = new class {
  private $num;

  /**
   * @return mixed
   */
  public function getNum()
  {
    return $this->num;
  }

  /**
   * @param mixed $num
   */
  public function setNum($num)
  {
    $this->num = $num;
  }

};

$entity->setNum(1);

\kint::dump('before ' . $entity->getNum());
$arguments = ['entity' => $entity];

$hooks = \Nuntius\System\System::hooksDispatcher();
$hooks
  ->setArguments($arguments)
  ->alter('entity_create');

\kint::dump('after ' . $entity->getNum());
