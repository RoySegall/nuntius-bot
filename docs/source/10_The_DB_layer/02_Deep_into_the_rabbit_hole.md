As most of the API in Nuntius, the DB layer is accessible using a service. Three
ways are defined as best practice:

First, aliasing from Nuntius static methods:
```php
<?php
\Nuntius\Nuntius::getDb();
```

The second is just calling the service:
```php
<?php
\Nuntius\Nuntius::container()->get('db');
```

The third one is the best one when writing a service:

```yml
services:
  manager.entity:
    class: \Nuntius\EntityManager
    arguments: ['@db', '@config']
```

And you service should look like:

```php
<?php

namespace Nuntius;
use Nuntius\Db\DbDispatcher;

/**
 * Entity mananger.
 */
class EntityManager {

  function __construct(DbDispatcher $db, NuntiusConfig $config) {
    $this->db = $db;
  }
  /// ...
}
```

## Know you your methods

The `\Nuntius\Nuntius::getDb();` will get give you access to couple of methods:

* `Nuntius::getDb()->getQuery()`: The query part, which you know for the previous page, which allow
you to query the DB and get arrays of rows match your query.
* `Nuntius::getDb()->getStorage()`: CRUD(Create, Read, Update, Delete) operation on the table in the
DB.
* `Nuntius::getDb()->getOperations()`: Creating and delete databases, tables and
indexes(very hard core!).
* `Nuntius::getDb()->getMetadata()`: Get information about the DB: the type, 
description and text when the user will install Nuntius.

More information will be provided in the 
[Writing a custom driver](Writing_a_custom_driver/Writing_a_custom_driver.html)
part.
