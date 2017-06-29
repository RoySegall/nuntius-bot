<?php

namespace tests;
use Nuntius\Nuntius;

/**
 * Testing FB postbacks.
 */
class FbPostBacksTest extends TestsAbstract {

  /**
   * @var \Nuntius\FbPostBackManager
   */
  protected $fbPostBackManager;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->fbPostBackManager = Nuntius::getFbPostBackManager();
  }

  /**
   * Testing matching tasks from text.
   */
  public function testFindFbPostBack() {
    $this->assertInstanceOf('\Nuntius\FacebookPostBacks\TossACoin', $this->fbPostBackManager->getPostBack('toss_a_coin'));
    $this->assertInstanceOf('\Nuntius\FacebookPostBacks\WhatIsMyName', $this->fbPostBackManager->getPostBack('what_is_my_name'));
    $this->assertInstanceOf('\Nuntius\FacebookPostBacks\SomethingNice', $this->fbPostBackManager->getPostBack('something_nice'));
  }

}
