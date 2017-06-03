At the beginning the DB layer was designed to work with only 
[RethinkDB](http://www.rethinkdb.com) but, one of Nuntius ideas is that 98% of
the components are swappable - any one can replace any core components since all
of the integration specified in the `settings.yml` file.

So, the DB layer was abstracted and split into four elements(which we will cover
in the upcoming parts). Another reason is the on boarding process of new
developers which need to know how to talk with RethinkDB.

Before the change you would need to do something like:

```php
<?php
  \Nuntius\Nuntius::getRethinkDB()
    ->getTable('running_context')
    ->filter(\r\row('foo')->eq('bar'))
    ->filter(\r\row('bar')->ne('fo'))
    ->run($this->db->getConnection())
    ->toArray();
```

Not very nice and intuitive. After the abstraction you need to do:

```php
<?php
  \Nuntius\Nuntius::getDb()
    ->getQuery()
    ->table('running_context')
    ->condition('foo', 'bar')
    ->condition('bar', 'fo')
    ->execute();
```

Much better. Go [Deep into the rabbit hole](The_DB_layer/Deep_into_the_rabbit_hole.html) part to
see the available methods and what you can do.
