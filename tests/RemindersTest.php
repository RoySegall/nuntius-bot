<?php

namespace tests;

class RemindersTest extends TestsAbstract {

  /**
   * Reminders when the user log in.
   *
   * @covers Reminder::RemindMe()
   */
  public function testRemindWhenUserLogIn() {
    $results = $this
      ->nuntius
      ->setAuthor('Hal2000')
      ->getPlugin('@nuntius remind me next time I log in to do burpee');

    $this->assertEquals($results, 'OK! I will remind you next you\'ll log in.');

    $results = $this->rethinkdb
      ->getTable('reminders')
      ->filter(\r\row('to')->eq('Hal2000'))
      ->run($this->rethinkdb->getConnection());

    $this->assertTrue(!empty($results->toArray()), 'There are no reminders in the DB.');
  }

  /**
   * Testing reminders for other users.
   *
   * @covers Reminder::RemindTo()
   */
  public function testRemindToOtherUser() {
    $results = $this->nuntius->setAuthor('Hal2000')->getPlugin('when Major Tom is logged in tell him he should tell me hi');

    $this->assertEquals($results, "Don't worry! I got your back. I'll send him a reminder.");

    $results = $this->rethinkdb
      ->getTable('reminders')
      ->filter(\r\row('to')->eq('Major Tom'))
      ->filter(\r\row('author')->eq('Hal2000'))
      ->run($this->rethinkdb->getConnection());

    $this->assertTrue(!empty($results->toArray()), 'There are no reminders in the DB.');
  }

  /**
   * Testing for deleting reminders for a user.
   *
   * @covers Reminder::deleteReminderForFrom()
   */
  public function testDeleteRemindersFor() {
    // Set up reminders for Major tom and Ground control.
    $this->nuntius->setAuthor('Hal2000')->getPlugin('@nuntius when Major Tom is logged in tell him he should tell me hi');
    $this->nuntius->setAuthor('Hal2000')->getPlugin('@nuntius when Ground control is logged in tell him he should tell me hi');

    $this->nuntius->setAuthor('Hal2000')->getPlugin('@nuntius forget about the reminders for Major Tom');

    // Get results of Major tom and John.
    $ground_control_reminders = $this->rethinkdb
      ->getTable('reminders')
      ->filter(\r\row('author')->eq('Hal2000'))
      ->filter(\r\row('to')->eq('Ground control'))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    $MAJORTom_reminders = $this->rethinkdb
      ->getTable('reminders')
      ->filter(\r\row('author')->eq('Hal2000'))
      ->filter(\r\row('to')->eq('Major Tom'))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    $this->assertTrue(!empty($ground_control_reminders), 'The reminders for Ground control was not removed.');
    $this->assertTrue(empty($MAJORTom_reminders), 'The reminders for Ground control was not removed.');
  }

  /**
   * Testing for deleting all the reminders.
   *
   * @covers Reminder::DeleteAllReminderOfUser()
   */
  public function testDeleteAllReminders() {
    // Set up reminders for Major tom and John.
    $this->nuntius->setAuthor('Hal2000')->getPlugin('@nuntius remind me next time I log in to do burpee');
    $this->nuntius->setAuthor('Major tom')->getPlugin('@nuntius remind me next time I log in to turn the engine on');

    // Checking the plugin return the correct results.
    $results = $this
      ->nuntius
      ->setAuthor('Hal2000')
      ->getPlugin('@nuntius delete all the reminders I asked from you');

    $this->assertEquals($results, 'What reminder? :wink:');

    // Get results of Major tom and John.
    $ground_control_reminders = $this->rethinkdb
      ->getTable('reminders')
      ->filter(\r\row('author')->eq('Hal2000'))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    $MJRTom_reminders = $this->rethinkdb
      ->getTable('reminders')
      ->filter(\r\row('author')->eq('Major tom'))
      ->run($this->rethinkdb->getConnection())
      ->toArray();

    $this->assertTrue(empty($ground_control_reminders), 'The reminders for Ground control was not removed.');
    $this->assertTrue(!empty($MJRTom_reminders), 'The reminders for Major tom removed.');
  }

}
