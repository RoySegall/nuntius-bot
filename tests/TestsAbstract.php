<?php

namespace tests;

use Nuntius\Nuntius;

/**
 * Abstract classes for tests.
 */
abstract class TestsAbstract extends \PHPUnit_Framework_TestCase {

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

      Nuntius::getRethinkDB()->truncateTable($table);
    }
  }

}
