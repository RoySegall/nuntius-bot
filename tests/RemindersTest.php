<?php

namespace tests;

class RemindersTest extends TestsAbstract {

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
