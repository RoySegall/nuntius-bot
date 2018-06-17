<?php

namespace Nuntius\System\Hooks;

use Nuntius\Db\DbDispatcher;
use Nuntius\System\EntityPluginManager;
use Nuntius\System\HookBaseClass;
use Nuntius\System\HookContainerInterface;

class CapsuleInstall extends HookBaseClass implements HookContainerInterface {

  /**
   * @var EntityPluginManager
   */
  protected $entityPluginManager;

  /**
   * CapsuleInstall constructor.
   *
   * @param EntityPluginManager $entity_plugin_manager
   */
  public function __construct(EntityPluginManager $entity_plugin_manager) {
    $this->entityPluginManager = $entity_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function invoke($arguments) {
    // Get all the entity plugins for that capsule.
    $entities = $this->entityPluginManager->getEntitiesList();

    d('a');

    foreach ($entities as $entity) {
      if ($entity['provided_by'] != $arguments['capsule']) {
        continue;
      }

      $entity->installEntity();
    }
  }

  /**
   * {@inheritdoc}
   */
  static function getContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container) {
    return new static($container->get('entity.plugin_manager'));
  }
}
