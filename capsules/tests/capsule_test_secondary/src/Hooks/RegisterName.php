<?php

namespace Nuntius\CapsuleTestSecondary\Hooks;

use Nuntius\Db\DbDispatcher;
use Nuntius\System\HookBaseClass;
use Nuntius\System\HookContainerInterface;

class RegisterName extends HookBaseClass implements HookContainerInterface {

  /**
   * @var DbDispatcher
   */
  protected $dbDispatcher;

  /**
   * RegisterName constructor.
   * @param DbDispatcher $db_dispatcher
   */
  public function __construct(DbDispatcher $db_dispatcher) {
    $this->dbDispatcher = $db_dispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public function invoke($arguments) {
    $this->dbDispatcher->getStorage()->table('names')->save(['name' => 'Tom']);
  }

  /**
   * {@inheritdoc}
   */
  public function alter(&$arguments) {
    foreach ($arguments as &$argument) {
      if ($argument == 'Tom') {
        $argument = 'Maj. Tom';
      }
    }
  }

  /**
   * Using the container to use dependency injection to hooks.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   * @return mixed
   * @throws \Exception
   */
  static function getContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container) {
    return new self($container->get('db'));
  }
}