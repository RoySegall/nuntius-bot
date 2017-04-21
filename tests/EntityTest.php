<?php

namespace tests;
use Nuntius\EntityBase;
use Nuntius\Nuntius;

/**
 * Testing entity.
 */
class EntityTest extends TestsAbstract {

  /**
   * entity manager.
   *
   * @var \Nuntius\EntityManager
   */
  protected $entities;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->entities = Nuntius::getEntityManager();
  }

  /**
   * Testing entities crud operation.
   */
  public function testEntitiesCrud() {
    foreach (array_keys(Nuntius::getSettings()['entities']) as $entity) {
      $this->crudOperations($entity);
    }
  }

  /**
   * Running the crud operations.
   *
   * @param string $entity_type
   *   The entity type.
   */
  protected function crudOperations($entity_type) {
    /** @var EntityBase $entity */
    $entity = $this->entities->get($entity_type);

    // Create entity.
    $object = $entity->insert(['title' => 'foo']);

    // Query the entity.
    $result = $this->rethinkdb
      ->getTable($entity_type)
      ->filter(\r\row('title')->eq('foo'))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    $array_copy = reset($result)->getArrayCopy();
    $this->assertEquals($object->id, $array_copy['id']);
    $this->assertArrayHasKey('time', $array_copy);

    // Load entity.
    $this->assertEquals($entity->load($array_copy['id'])->id, $object->id);

    // Update entity.
    $entity->update($object->id, ['bar' => 'foo']);
    $fresh_copy = $entity->load($object->id);
    $this->assertEquals($fresh_copy->bar, 'foo');
    $this->assertEquals($fresh_copy->title, 'foo');

    // Delete the entity.
    $entity->delete($fresh_copy->id);
    $this->assertFalse($entity->load($fresh_copy->id));
  }

}
