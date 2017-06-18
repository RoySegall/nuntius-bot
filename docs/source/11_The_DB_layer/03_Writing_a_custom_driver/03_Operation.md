The operation part is the one of the hard core parts in the driver; It's suppose
to handle creation and deletion of databases, tables and indexes:

```php
<?php

namespace Nuntius\Db\RethinkDB;

use Nuntius\Db\DbOperationHandlerInterface;
use Nuntius\Nuntius;

/**
 * RethinkDB operation handler.
 */
class RethinkDbOperationHandler implements DbOperationHandlerInterface {

  /**
   * The rethinkDB service.
   *
   * @var \Nuntius\NuntiusRethinkdb
   */
  protected $rethinkDB;

  /**
   * The connection object.
   *
   * @var \r\Connection
   */
  protected $connection;

  /**
   * The DB name.
   *
   * @var string
   */
  protected $db;

  /**
   * Constructing.
   */
  function __construct() {
    $this->rethinkDB = @Nuntius::getRethinkDB();
    $this->connection = $this->rethinkDB->getConnection();
    $this->db = Nuntius::getSettings()->getSetting('rethinkdb')['db'];
  }

  /**
   * {@inheritdoc}
   */
  public function connected() {
    return $this->connection;
  }

  /**
   * {@inheritdoc}
   */
  public function getError() {
    return $this->rethinkDB->error;
  }

  /**
   * {@inheritdoc}
   */
  public function dbCreate($db) {
    \r\dbCreate($db)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function dbDrop($db) {
    \r\dbDrop($db)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function dbList() {
    return \r\dbList()->run($this->connection);
  }

  /**
   * {@inheritdoc}
   */
  public function dbExists($db) {
    return in_array($db, $this->dbList());
  }

  /**
   * {@inheritdoc}
   */
  public function tableCreate($table) {
    \r\db($this->db)->tableCreate($table)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function tableDrop($table) {
    \r\db($this->db)->tableDrop($table)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function tableList() {
    return \r\db($this->db)->tableList()->run($this->connection);
  }

  /**
   * {@inheritdoc}
   */
  public function tableExists($table) {
    return in_array($table, $this->tableList());
  }

  /**
   * {@inheritdoc}
   */
  public function indexCreate($table, $column) {
    \r\db($this->db)->table($table)->indexCreate($column)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function indexDrop($table, $column) {
    \r\db($this->db)->table($table)->indexDrop($column)->run($this->connection);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function indexList($table) {
    return \r\db($this->db)->table($table)->indexList()->run($this->connection);
  }

  /**
   * Making sure the index exists in a table.
   *
   * @param $table
   *   The table name.
   * @param $column
   *   The columns name.
   *
   * @return bool
   */
  public function indexExists($table, $column) {
    return in_array($column, $this->indexList($table));
  }

}

```

The code is pretty much self explanatory but it's contains a template on which
we can understand the code better:

*Please note that X is relate to the part we need to take care of: db, table or
index AKA responsibility segment*

`XCreate()`: Create X in the responsibility segment.

`XDrop()`: Remove X from responsibility segment.

`XList()`: Return list of X responsibility segment.

`XExists()`: Check if we already have a matching entity in the responsibility 
segment.
