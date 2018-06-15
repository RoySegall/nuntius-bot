<?php

namespace tests;

use Nuntius\System\EntityPluginManager;
use Nuntius\System\Plugin\Entity\System;

/**
 * Testing entity.
 */
class EntityTest extends TestsAbstract {

  protected $services = [
    'entityManager' => 'entity.plugin_manager',
  ];

  protected $capsules = ['system'];

  /**
   * @var EntityPluginManager
   */
  protected $entityManager;

  /**
   * Testing entities crud operation.
   */
  public function testEntitiesCrud() {
    /** @var System $entity */
    $entity = $this->entityManager->createInstance('system');

    $entity->name = 'Testing';
    $entity->machine_name = 'testing';
    $entity->description = 'testing entity';
    $entity->path = '.';
    $entity->status = 1;
    $object = $entity->save();

    $result = $this->query
      ->table('system')
      ->condition('name', 'Testing')
      ->execute();

    $array_copy = reset($result);

    $this->assertEquals($object['id'], $array_copy['id']);
    $this->assertArrayHasKey('time', $array_copy);

    // Load entity.
    $this->assertEquals($entity->load($array_copy['id'])->id, $object['id']);

    // Update entity.
    $entity = $entity->load($object['id']);
    $entity->name = 'foo';
    $entity->update();

    $fresh_copy = $entity->load($object['id']);
    $this->assertEquals($fresh_copy->name, 'foo');

    // Delete the entity.
    $entity->delete($fresh_copy->id);
    $this->assertFalse($entity->load($fresh_copy->id));
  }

  /**
   * Test the hooks of the entity system.
   */
  public function testEntityHooks() {

  }

  /**
   * Test the relationships between entities.
   */
  public function testEntityRelationships() {

  }

}
