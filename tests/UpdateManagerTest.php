<?php

namespace tests;

use Nuntius\Nuntius;

/**
 * Testing entity.
 */
class UpdateManagerTest extends TestsAbstract {

  /**
   * The entity manager.
   *
   * @var \Nuntius\UpdateManager
   */
  protected $updateManger;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->updateManger = Nuntius::getUpdateManager();

    // Empty the processed updates.
    Nuntius::getEntityManager()->get('system')->update('updates', ['processed' => []]);
  }

  /**
   * Testing update manager.
   */
  public function testEntitiesCrud() {
    $this->assertEquals($this->updateManger->getUpdates(), $this->updateManger->getUnProcessedUpdates());

    // Run the updates. Checking they not throw any error.
    foreach ($this->updateManger->getUpdates() as $name => $update) {
      $update->update();
      $this->updateManger->addProcessedUpdate($name);
    }

    $this->assertEquals(array_keys($this->updateManger->getUpdates()), $this->updateManger->getDbProcessedUpdates());
  }

}
