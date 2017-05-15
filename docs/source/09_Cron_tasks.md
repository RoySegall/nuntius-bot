If you need to run command in a constant period of time such as: sending
messages in specific hours, process a lot of data in the DB, you need cron 
tasks.

To enable the cron task you can add in the cront task the next code:
```
*/1 * * * * PATH_TO_PHP PATH_TO_NUNTIUS/cron.php >/dev/null 2>&1
```
***If you see that the cron task is not triggered, try to give 777 permission to
the file.***

The cron task will be fired each minutes but that should not bother you. Let's
have a look on the code.

First, the `hooks.yml` file:

```yml
cron:
  log: '\Nuntius\Cron\LogThings'
```

Next, let's look on how to define the cron job:
```php
<?php

namespace Nuntius\Cron;

class LogThings extends CronTaskAbstract implements CronTaskInterface {

  /**
   * {@inheritdoc}
   */
  protected $period = '*/5 * * * *';

  /**
   * {@inheritdoc}
   */
  public function run() {
    // ...
  }

}
```

The `$period` property will tell to cron what is the periodic rule it's 
follows. In this case each five minutes.

The next par is the `run` method. In that method you'll apply the logic of the
the task.
