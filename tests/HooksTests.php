<?php

namespace tests;

use Nuntius\Capsule\CapsuleService;
use Nuntius\Db\DbDispatcher;
use Nuntius\System\HooksDispatcher;

/**
 * Testing entity.
 */
class HooksTests extends TestsAbstract {

  protected $capsules = ['capsule_test_secondary'];

  /**
   * @var array
   *
   * List of services.
   */
  protected $services = [
    'capsuleService' => 'capsule_manager',
    'db' => 'db',
    'hooksDispatcher' => 'hooks_dispatcher',
  ];

  /**
   * @var CapsuleService
   */
  protected $capsuleService;

  /**
   * @var DbDispatcher
   */
  protected $db;

  /**
   * @var HooksDispatcher
   */
  protected $hooksDispatcher;

  public function setUp() {
    parent::setUp();

    $this->db->getOperations()->tableCreate('names');
  }

  /**
   * Testing hooks integration.
   */
  public function testHooks() {
    $this->hooksDispatcher->invoke('register_names');

    $results = $this->db->getStorage()->table('names')->loadMultiple();

    $this->assertCount(2, $results);

    $names = array_map(function($item) {
      return $item['name'];
    }, $results);

    $this->assertTrue(in_array('Tom', $names));
    $this->assertTrue(in_array('HAL 2000', $names));

    $this
      ->hooksDispatcher
      ->setArguments($names)
      ->alter('register_names');

    $this->assertTrue(in_array('Maj.Tom', $names));
    $this->assertTrue(in_array('Hal2000', $names));
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown() {
    parent::tearDown();

    $this->db->getOperations()->tableDrop('names');
  }

}
