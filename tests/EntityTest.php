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
    /** @var System $system */
    $system = $this->entityManager->createInstance('system');
    $errors = $system->validate(TRUE);

    $this->assertEquals($errors['name'], ['This value should not be blank.']);
    $this->assertEquals($errors['description'], ['This value should not be blank.']);
    $this->assertEquals($errors['machine_name'], ['This value should not be blank.']);
    $this->assertEquals($errors['path'], ['This value should not be blank.']);
    $this->assertEquals($errors['status'], ['This value should not be blank.']);


    $system->path = time();
    $errors = $system->validate(TRUE);

    $this->assertContains('does not exists', $errors['path'][0]);
  }

  /**
   * Checking the hook for after saving.
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function testEntitySave() {
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
  public function testEntityLoad() {
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
  public function testEntityCreate() {
    $entity = $this->createSystem();
    $this->assertEquals($entity->description, 'testing entity');
    $this->capsuleService->enableCapsule('capsule_test_secondary');
    $entity = $this->createSystem();
    $this->assertEquals($entity->description, 'new description');
  }

  /**
   * Testing integration with the update mechanism.
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function testEntityUpdate() {
    $this->capsuleService->enableCapsule('capsule_test_secondary');
    $entity = $this->createSystem();

    $entity->name = 'checking';
    $new_entity = $entity->save();

    $this->assertEquals($new_entity->name, 'checking updated');
  }

  /**
   * Testing entity deletion integration.
   *
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  public function testEntityDelete() {
    $this->capsuleService->enableCapsule('capsule_test_secondary');

    $entity = $this->createSystem();

    try {
      $entity->delete($entity->id);
    } catch (\Exception $e) {
      $this->assertEquals($e->getMessage(), 'Deleted ' . $entity->id);
    }
  }

  /**
   * Test the relationships between entities.
   */
  public function testEntityRelationships() {
    $this->capsuleService->enableCapsule('capsule_test_main');

    /** @var \Nuntius\CapsuleTestMain\Plugin\Entity\TagSingleRelation $tag_single */
    $tag_single = $this->entityManager->createInstance('tag_single_relation');
    /** @var \Nuntius\CapsuleTestMain\Plugin\Entity\TagMultipleRelation $tag_multiple */
    $tag_multiple = $this->entityManager->createInstance('tag_many_relation');

    /** @var \Nuntius\CapsuleTestMain\Plugin\Entity\Vocabulary $vocabulary */
    $vocabulary = $this->entityManager->createInstance('vocabulary');

    $vocabulary->name = 'Testing vocab';
    $vocabulary = $vocabulary->save();

    $tag_multiple->vocabulary = 'a';
    $errors = $tag_multiple->validate(TRUE);
    $this->assertEquals($errors['vocabulary'], ['The relation type is multiple thus cannot be a single ID']);

    $tag_multiple->vocabulary = ['a'];
    $errors = $tag_multiple->validate(TRUE);

    $this->assertEquals($errors['vocabulary'], ['The IDs: a are not a valid IDs for the entity tag_many_relation']);

    $tag_multiple->vocabulary = [$vocabulary->id];
    $tag = $tag_multiple->save();
    $this->assertNotFalse($tag->id);

    $tag_single->vocabulary = ['a'];
    $errors = $tag_single->validate(TRUE);

    $this->assertEquals($errors['vocabulary'], ['The relation type is single thus cannot be a multiple list of IDs']);

    $tag_single->vocabulary = 'a';
    $errors = $tag_single->validate(TRUE);

    $this->assertEquals($errors['vocabulary'], ['There is no matching vocabulary entity with the ID a']);

    $tag_single->vocabulary = $vocabulary->id;
    $tag = $tag_single->save();
    $this->assertNotFalse($tag->id);
  }

}
