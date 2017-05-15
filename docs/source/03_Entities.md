At some point you might want to keep stuff in the DB. The database is based on 
Rethinkdb. Similar to event integration definition, entity defined in the 
`hooks.yml` file:
```yml
entities:
  reminders: '\Nuntius\Entity\Reminders'
  context: '\Nuntius\Entity\Context'
  context_archive: '\Nuntius\Entity\RunningContext'
  running_context: '\Nuntius\Entity\RunningContext'
  system: '\Nuntius\Entity\System'
```

You could implement methods relate to the entity in the matching class but you
will see that the basic methods are enough.

### Add an entry

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->insert(['foo' => 'bar']);
```

### Load an entry

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->load(ID);
```

### Load all the entries

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->loadAll();
```

### Update an entry

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->update(ID, ['foo' => 'bar']);
```

### Delete an entry

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->delete(ID);
```

### Query in the DB
Except for the CRUD layer, sometimes you need to look for items. Have a look at
the code:
```php
<?php
  \Nuntius\Nuntius::getRethinkDB()
    ->getTable('running_context')
    ->filter(\r\row('foo')->eq('bar'))
    ->filter(\r\row('bar')->ne('fo'))
    ->run($this->db->getConnection())
    ->toArray();
```
