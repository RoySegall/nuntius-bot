<?php

require 'autoloader.php';

$resolver = new \Cron\Resolver\ArrayResolver();

$tasks = \Nuntius\Nuntius::getCronTasksManager()->getCronTasks();

// Get all the tasks.
foreach ($tasks as $cron_task) {
  $job = new \Cron\Job\ShellJob();
  $job->setCommand('echo "a"');
  $job->setSchedule(new \Cron\Schedule\CrontabSchedule($cron_task->getPeriod()));

  if ($job->valid(new DateTime())) {
    // Running the command here and not via a shell command since that's did not
    // worked and the command never ran.
    $cron_task->run();
  }

  // Register the task.
  $resolver->addJob($job);
}

$cron = new \Cron\Cron();
$cron->setExecutor(new \Cron\Executor\Executor());
$cron->setResolver($resolver);

$cron->run();
