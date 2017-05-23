<?php

namespace tests;
use Nuntius\Nuntius;
use Nuntius\Tasks\Introduction;
use Nuntius\Tasks\NotifyTeam;
use Nuntius\Tasks\Reminders;
use Nuntius\Tasks\RestartQuestion;
use Nuntius\TasksManager;
use Slack\RealTimeClient;

/**
 * Testing tasks.
 */
class TasksTest extends TestsAbstract {

  /**
   * List of events.
   *
   * @var \Nuntius\TasksManager
   */
  protected $tasks;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->tasks = Nuntius::getTasksManager();
  }

  /**
   * Testing matching tasks from text.
   *
   * @covers TasksManager::getMatchingTask()
   * @covers TasksManager::get()
   */
  public function testGetMatchingTask() {
    $this->assertEquals($this->tasks->getMatchingTask('help'), [
      $this->tasks->get('help'),
      'listOfScopes',
      []
    ]);

    $this->assertEquals($this->tasks->getMatchingTask('remind me to help you'), [
      $this->tasks->get('reminders'),
      'addReminder',
      [1 => 'to help you'],
    ]);

    $this->assertEquals($this->tasks->getMatchingTask('nice to meet you'), [
      $this->tasks->get('introduction'),
      '',
      [],
    ]);
  }

  /**
   * Testing return of all tasks.
   *
   * @covers TasksManager::getTasks()
   */
  public function testGetTasks() {
    $this->assertEquals($this->tasks->getTasks(), [
      'reminders' => $this->tasks->get('reminders'),
      'help' => $this->tasks->get('help'),
      'introduction' => $this->tasks->get('introduction'),
      'restart_question' => $this->tasks->get('restart_question'),
      'notify_team' => $this->tasks->get('notify_team'),
    ]);
  }

  /**
   * Testing reminder set up.
   */
  public function testReminders() {
    $this->tasks->get('reminders')
      ->setData(['user' => 'Major. Tom'])
      ->addReminder('foo bar is my best stuff!');

    $results = $this->rethinkdb
      ->getTable('reminders')
      ->filter(\r\row('user')->eq('Major. Tom'))
      ->filter(\r\row('reminder')->eq('foo bar is my best stuff!'))
      ->run($this->rethinkdb->getConnection());

    $this->assertEquals(count($results->toArray()), 1);
  }

  /**
   * Testing help.
   */
  public function testHelp() {
    $helps = [
      '`remind me REMINDER`: Next time you log in I will remind you what you  wrote in the REMINDER',
      '`nice to meet you`: We will do a proper introduction',
      '`delete information`: Delete an information',
      '`notify team`: Notify the team about something',
    ];
    $this->assertEquals($this->tasks->get('help')->listOfScopes(), implode("\n", $helps));
  }

  /**
   * Testing introduction plugin.
   */
  public function testIntroduction() {
    /** @var Introduction $introduction */
    $introduction = $this->tasks->get('introduction');
    $this->assertEquals('Oh hey! It look that we are not introduced yet. what is your first name?', $introduction->startTalking());
    $introduction->setAnswer('Major.');
    $this->assertEquals('what is your last name?', $introduction->startTalking());
    $introduction->setAnswer('Tom');
    $this->assertEquals('Well, Major. Tom, it is a pleasure.', $introduction->startTalking());
  }

  /**
   * Testing introduction plugin.
   */
  public function testRestartQuestion() {
    // Get the list of all the tasks.
    $tasks = Nuntius::getTasksManager()->getRestartableTasks();

    $labels = [];
    foreach ($tasks as $task) {
      $labels[] = '`' . $task['label'] . '`';
    }

    // Get all the un-temp context question.
    $text = "So... You want to delete information of a question. For which question?\n" . implode(',', $labels);

    /** @var RestartQuestion $restart */
    $restart = $this->tasks->get('restart_question');
    $this->assertEquals($text, $restart->startTalking());
    $this->assertEquals($restart->setAnswer('foo'), 'Hmmm..... it\'s look like `foo` is not a task I know.');
    $restart->setAnswer('nice to meet you');
    $this->assertEquals('Do you want to start the process again or should I restart the question?', $restart->startTalking());
    $this->assertEquals($restart->setAnswer('maybe'), 'The answer need to be one of the following: `yes`, `no`, `y`, `n`');
    $restart->setAnswer('no');
    $this->assertEquals('I deleted for you the information.', $restart->startTalking());

    // Now, checking with the restart question.
    $restart = $this->tasks->get('restart_question');
    $this->assertEquals($text, $restart->startTalking());
    $this->assertEquals($restart->setAnswer('foo'), 'Hmmm..... it\'s look like `foo` is not a task I know.');
    $restart->setAnswer('nice to meet you');
    $this->assertEquals('Do you want to start the process again or should I restart the question?', $restart->startTalking());
    $this->assertEquals($restart->setAnswer('maybe'), 'The answer need to be one of the following: `yes`, `no`, `y`, `n`');
    $restart->setAnswer('yes');

    $answer = $restart->startTalking();
    $this->assertContains("I deleted for you the information.", $answer);
    $this->assertContains("Oh hey! It look that we are not introduced yet. what is your first name?", $answer);
  }

  /**
   * Testing the notify team task.
   */
  public function testNotifyTeam() {
    /** @var NotifyTeam $restart */
    $restart = $this->tasks->get('notify_team');

    $this->assertEquals('Who are your team members? Use a comma(,) after a username.', $restart->startTalking());
    $restart->setAnswer('Major. Tom, Hal 9000');
    $this->assertEquals('What should I tell them?', $restart->startTalking());
    $restart->setAnswer('There is a leak on the boat!');
    $this->assertEquals('Awesome! I notified the team.', $restart->startTalking());
  }

}
