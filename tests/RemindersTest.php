<?php

class RemindersTest extends PHPUnit_Framework_TestCase {

  /**
   * @var \Prophecy\Prophecy\ObjectProphecy|\Nuntius\Nuntius
   */
  protected $nuntius;

  public function __construct($name = NULL, array $data = [], $dataName = '') {
    parent::__construct($name, $data, $dataName);

    $this->nuntius = new \Nuntius\Nuntius();

    $this->nuntius->addPlugins(New \Nuntius\Plugins\Reminder());
    $this->nuntius->addPlugins(New \Nuntius\Plugins\SmallTalk());
    $this->nuntius->addPlugins(New \Nuntius\Plugins\Help());
  }

  /**
   * Test reminders.
   */
  public function testReminders() {
    $results = $this->nuntius->getPlugin('@nuntius delete all the reminders I asked from you');
    $this->assertEquals($results, 'What reminder? :wink:');

    $results = $this->nuntius->getPlugin('@nuntius asdasdasd');
    $this->assertEquals($results, NULL);

    // todo: check something happens in the DB.
  }

}
