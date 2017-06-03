
In order to test GitHub webhooks set the local env variable `NUNTIUS_BASE_URL`
to the address of your nuntius installation:

```bash
export NUNTIUS_BASE_URL=http://localhost:8888
```

After that fire up the test server:
```php
php -S localhost:8888
```

Running tests is easy:

```bash
bash tests.sh
```
