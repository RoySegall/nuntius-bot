<?php

namespace Nuntius\System;

use Nuntius\Db\DbDispatcher;

class EntityBase implements HookContainerInterface {

  /**
   * @var DbDispatcher
   */
  protected $dbDispatcher;

  /**
   * @var \Nuntius\Db\DbStorageHandlerInterface
   */
  protected $storage;

  /**
   * @var HooksDispatcherInterface
   */
  protected $hooksDispatcher;

  /**
   * EntityBase constructor.
   * @param DbDispatcher $db
   * @param HooksDispatcherInterface $hooks_dispatcher
   */
  public function __construct(DbDispatcher $db, HooksDispatcherInterface $hooks_dispatcher) {
    $this->dbDispatcher = $db;
    $this->storage = $db->getStorage();
    $this->hooksDispatcher = $hooks_dispatcher;
  }

  /**
   * Using the container to use dependency injection to hooks.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   * @return mixed
   * @throws \Exception
   */
  static function getContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container) {
    return new self($container->get('db'), $container->get('hooks_dispatcher'));
  }

  /**
   * {@inheritdoc}
   */
  public function loadMultiple(array $ids = []) {
    $results = [];

//    foreach ($this->storage->loadMultiple($ids) as $result) {
//      $results[$result['id']] = $this->createInstance($result);
//    }

    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function load($id) {
    $results = $this->loadMultiple([$id]);
    return reset($results);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $item) {
//    return $this->storage->save($item);
  }

  /**
   * {@inheritdoc}
   */
  public function delete($id) {
//    $this->storage->delete($id);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteMultiple(array $ids = []) {
//    $this->storage->delete($ids);
  }

  /**
   * {@inheritdoc}
   */
  public function update($data) {
//    $this->storage->update($data);
  }

  public function getTable() {

  }

  public function validate($return_errors = FALSE) {
  }

  public function beforeSave() {
  }

  public function afterSave() {
  }

  public function beforeLoad() {
  }

  public function afterLoad() {
  }

  public function access($op) {
  }

}
