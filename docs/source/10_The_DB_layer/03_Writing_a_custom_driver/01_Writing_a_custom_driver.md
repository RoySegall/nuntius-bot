Writing a custom DB driver comes for one reason only: you love a DB. That DB is
part of your daily stack and you don't want to to start using another DB. That's
OK. As any integration, we will start with the `hooks.yml` file. Let's see how
we added `rethinkdb` into the `db_drivers`:
```yml
db_drivers:
  rethinkdb:
    metadata: '\Nuntius\Db\RethinkDB\RethinkDbMetadataHandler'
    operations: '\Nuntius\Db\RethinkDB\RethinkDbOperationHandler'
    query: '\Nuntius\Db\RethinkDB\RethinkDbQueryHandler'
    storage: '\Nuntius\Db\RethinkDB\RethinkDbStorageHandler'
```

The order of the items is not mandatory but could be much more easy to implement
in that order.
