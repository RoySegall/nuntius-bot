<?php

namespace Nuntius\System\Plugin\Cache;

use Nuntius\Db\DbDispatcher;
use Nuntius\System\CacheBase;
use Nuntius\System\Annotations\Cache as cache;
use Nuntius\System\HookContainerInterface;

/**
 * @cache(
 *   id = "db",
 * )
 */
class DBCache extends CacheBase implements HookContainerInterface {

  /**
   * The DB dispatcher service.
   *
   * @var DbDispatcher
   */
  protected $dbDispatcher;

  /**
   * Using the container to use dependency injection to hooks.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   * @return mixed
   *
   * @throws \Exception
   */
  static function getContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container) {
    return new static($container->get('db'));
  }

  /**
   * Constructor.
   *
   * @param DbDispatcher $db_dispatcher
   *  The DB dispatcher service.
   */
  function __construct(DbDispatcher $db_dispatcher) {
    $this->dbDispatcher = $db_dispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public function install() {
    if ($this->dbDispatcher->getOperations()->tableExists('cache')) {
      return;
    }

    $this->dbDispatcher->getOperations()->tableCreate('cache');
  }

  /**
   * {@inheritdoc}
   */
  public function clear($cid = NULL) {
    $storage = $this->dbDispatcher->getStorage()->table('cache');

    if ($cid) {
      if (is_array($cid)) {
        $storage->deleteMultiple($cid);
      }
      else {
        $storage->delete($cid);
      }

      return;
    }

    // Delete all.
    $storage->deleteMultiple();
  }

  /**
   * {@inheritdoc}
   */
  public function get($cid) {
    $caches = $this->getMultiple([$cid]);

    return reset($caches);
  }

  /**
   * {@inheritdoc}
   */
  public function getMultiple($cids) {
    $results = $this->dbDispatcher
      ->getQuery()
      ->table('cache')
      ->condition('id', $cids, 'IN')
      ->condition('expires', time(), '>=')
      ->execute();

    foreach ($results as &$result) {
      $result['content'] = ($result['content']);
    }

    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function set($id, $content, $expires = NULL) {
    if (!$expires) {
      $expires = time() + (86400 * 365 * 5);
    }

    $data = [
      'id' => $id,
      'content' => ($content),
      'expires' => $expires,
    ];

    $this->dbDispatcher
      ->getStorage()
      ->table('cache')
      ->save($data);

    return $data;
  }

}
