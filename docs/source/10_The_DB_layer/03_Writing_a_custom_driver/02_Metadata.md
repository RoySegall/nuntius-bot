The metadata part will describe the DB and what it can do. Let's take a look on
RethinkDB:

```php
<?php

/**
 * RethinkDB metadata handler.
 */
class RethinkDbMetadataHandler implements DbMetadataHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function dbType() {
    return 'NoSQL';
  }

  /**
   * {@inheritdoc}
   */
  public function installerDescription() {
    return 'No SQL light weight DB with real time support.';
  }

  /**
   * {@inheritdoc}
   */
  public function supportRealTime() {
    return TRUE;
  }

}
```

`dbType()`: What the DB type: A NoSQL or SQL.

`installerDescription()`: Small description on the DB. It will appear in the
installation and provide to the user information about the DB and why the choose 
the DB as the daily driver.

`supportRealTime()`: One of nuntius commands allows you to see live changes in 
a table. For the command to work, the DB metadata need to tell if the DB support
real time or not.