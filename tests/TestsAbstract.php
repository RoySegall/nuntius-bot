<?php

namespace tests;

abstract class TestsAbstract extends \PHPUnit_Framework_TestCase {

  /**
   * @var \Prophecy\Prophecy\ObjectProphecy|\Nuntius\Nuntius
   */
  protected $nuntius;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->nuntius = new \Nuntius\Nuntius();
  }

}
