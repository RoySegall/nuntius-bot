<?php

namespace tests;

use Nuntius\Capsule\CapsuleServiceInterface;
use Nuntius\System\EntityPluginManager;
use Nuntius\System\Plugin\Entity\System;

/**
 * Testing entity.
 */
class EntityTest extends TestsAbstract {

  protected $services = [
    'entityManager' => 'entity.plugin_manager',
    'capsuleService' => 'capsule_manager',
  ];

  protected $capsules = ['system'];

  /**
   * @var EntityPluginManager
   */
  protected $entityManager;

  /**
   * @var CapsuleServiceInterface
   */
  protected $capsuleService;

  /**
   * @return System
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  protected function createSystem() {
    /** @var System $entity */
    $entity = $this->entityManager->createInstance('system');

    $entity->name = 'Testing';
    $entity->machine_name = 'testing';
    $entity->description = 'testing entity';
    $entity->path = '.';
    $entity->status = 1;

    return $entity->save();
  }

  /**
   * Testing entities crud operation.
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function testEntitiesCrud() {
    /** @var System $entity */
    $entity = $this->entityManager->createInstance('system');
    $object = $this->createSystem();

    $result = $this->query
      ->table('system')
      ->condition('name', 'Testing')
      ->execute();

    $array_copy = reset($result);

    $this->assertEquals($object->id, $array_copy['id']);
    $this->assertArrayHasKey('time', $array_copy);

    // Load entity.
    $this->assertEquals($entity->load($array_copy['id'])->id, $object->id);

    // Update entity.
    $entity = $entity->load($object->id);
    $entity->name = 'foo';
    $entity->update();

    $fresh_copy = $entity->load($object->id);
    $this->assertEquals($fresh_copy->name, 'foo');

    // Delete the entity.
    $entity->delete($fresh_copy->id);
    $this->assertFalse($entity->load($fresh_copy->id));
  }

  /**
   * Testing the validation of the entity.
   */
  public function testValidation() {
  }

  /**
   * Checking the hook for after saving.
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function testAfterSave() {
    $this->capsuleService->enableCapsule('capsule_test_secondary');

    $entity = $this->createSystem();

    $this->assertEquals($entity->name, 'bar');
    $this->assertEquals($entity->foo, 'bar');
  }

  /**
   * Testing the hook after loading the entity.
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function testAfterLoad() {
    $entity = $this->createSystem();

    $loaded = $this->entityManager->createInstance('system')->load($entity->id);

    $this->assertEquals($loaded->name, 'Testing');
    $this->capsuleService->enableCapsule('capsule_test_secondary');
    $loaded = $this->entityManager->createInstance('system')->load($entity->id);

    $this->assertEquals($loaded->name, 'foo');
  }

  /**
   * Testing the hook before the entity data is constructed to the object.
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function testBeforeCreate() {
    $entity = $this->createSystem();
    $this->assertEquals($entity->description, 'testing entity');
    $this->capsuleService->enableCapsule('capsule_test_secondary');
    $entity = $this->createSystem();
    $this->assertEquals($entity->description, 'new description');
  }

  /**
   *
   */
  public function testAfterUpdate() {
  }

  /**
   *
   */
  public function testAfterDelete() {
  }

  /**
   * Test the relationships between entities.
   */
  public function testEntityRelationships() {
  }

}
