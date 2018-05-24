<?php

namespace tests;

use Nuntius\Nuntius;

/**
 * Abstract classes for tests.
 */
abstract class TestsAbstract extends \PHPUnit_Framework_TestCase {

  protected $services = [];

  /**
   * @var string[]
   */
  protected $tables;

  /**
   * @var \Nuntius\Db\DbQueryHandlerInterface
   */
  protected $query;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->query = Nuntius::getDb()->getQuery();

    foreach ($this->services as $property => $service) {
      $this->{$property} = Nuntius::container()->get($service);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown() {
    parent::tearDown();

    foreach (array_keys(Nuntius::getSettings()->getSetting('entities')) as $table) {

      if ($table == 'system') {
        // Don't truncate the update table.
        continue;
      }

      Nuntius::getDb()->getStorage()->table($table)->deleteMultiple();
    }

    // Delete all the capsules from the system.
    $results = Nuntius::getDb()->getQuery()
      ->table('system')
      ->condition('machine_name', "", "!=")
      ->execute();

    foreach ($results as $result) {
        Nuntius::getDb()->getStorage()->table('system')->delete($result['id']);
    }

  }

}
