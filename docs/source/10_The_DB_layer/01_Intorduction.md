This is an intro

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