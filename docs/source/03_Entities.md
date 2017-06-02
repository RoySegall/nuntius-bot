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

### Load multiple entries

You can ask for all of them:

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->loadMultiple();
```

Or you can ask for multiple entities:
```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->loadMultiple(['id1', 'id2', 'id3']);
```

### Update an entry

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->update(['id' => 'ID', 'foo' => 'bar']);
```

### Delete from the DB

You can delete a single entity:

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->delete('id');
```

You can multiple entities:

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->deleteMultiple(['id1', 'id2', 'id3']);
```

Or you can delete all the entities:

```php
<?php

\Nuntius\Nuntius::getEntityManager()
  ->get('context')
  ->deleteMultiple();
```