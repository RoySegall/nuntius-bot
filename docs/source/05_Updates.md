You deployed nuntius and you added some functionality but that functionality
needs some new entities tables, maybe change some information about the user 
etc. etc. For that case, we have the updates mechanism.

Implementing a new update:
```yml
updates:
  1: '\Nuntius\Update\Update1'
```

The code look pretty obvious:
```php
<?php

namespace Nuntius\Update;

class Update1 implements UpdateBaseInterface {

  /**
   * Describe what the update going to do.
   *
   * @return string
   *   What the update going to do.
   */
  public function description() {
    return 'Example update';
  }

  /**
   * Running the update.
   *
   * @return string
   *   A message for what the update did.
   */
  public function update() {
    return 'You run a simple update. Nothing happens but this update will not run again.';
  }

}
```

About the methods:
* `description`: explain what the update is going to do.
* `update`: Preform the update. The text the function will return will show 
after the update was invoked successfully.

A couple of rules:
1. Updates that invoked before won't invoked again.
2. The update will be invoked in the order in the yml file.
3. When installing nuntius, all the listed updates will be marked as updates 
which invoked already.
