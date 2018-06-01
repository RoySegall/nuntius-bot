<?php

namespace Nuntius\System;

use Nuntius\Db\DbDispatcher;

/**
 * Class EntityBase
 * @package Nuntius\System
 */
abstract class EntityBase implements HookContainerInterface {

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
    return new static($container->get('db'), $container->get('hooks_dispatcher'));
  }

  /**
   * Getter handler.
   *
   * Since this might be a NoSQL object we cannot predict the properties of the
   * class. In order to handle this one and prevent "Undefined property" errors
   * we need a getter that will check if the property exists or not.
   *
   * @param $name
   *  The name of the property.
   *
   * @return mixed
   *  Return the value of the property if exists, if not return NULL.
   */
  function __get($name) {
    if (!property_exists($this, $name)) {
      return NULL;
    }

    return $this->{$name};
  }

  /**
   * Return the current instance object with the values from the DB.
   *
   * @param array $data
   *   The data representation of the object.
   *
   * @return $this
   *   The current object.
   */
  protected function createInstance($data) {
    $this_copy = clone $this;

    foreach ($data as $property => $value) {
      $this_copy->{$property} = $value;
    }

    return $this_copy;
  }

  /**
   * {@inheritdoc}
   */
  public function loadMultiple(array $ids = []) {
    $results = [];

    $this->beforeLoad($ids);

    foreach ($this->storage->table($this->id)->loadMultiple($ids) as $result) {

      if (!$this->access('view', $result)) {
        continue;
      }

      $results[$result['id']] = $this->createInstance($result);
    }

    $this->afterLoad($results);

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
    $this->beforeSave($item, $this);
    $results = $this->storage->table($this->id)->save($item);
    $this->afterSave($results, $this);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function delete($id) {
    $this->storage->table($this->id)->delete($id);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteMultiple(array $ids = []) {
    $this->storage->table($this->id)->delete($ids);
  }

  /**
   * {@inheritdoc}
   */
  public function update($data) {
    $this->storage->table($this->id)->update($data);
  }

  /**
   * Pre-load hook.
   *
   * This function will be invoked before the ids are loaded from the DB. This
   * allow other modules to change or alter the list of IDs.
   *
   * @param $ids
   *  List of ids.
   */
  public function beforeLoad(&$ids) {
  }

  /**
   * Post-load hook.
   *
   * This function is invoked after the items have been loaded from the DB and
   * allow to other capsules alter the object values.
   *
   * @param $results
   *  List of items.
   */
  public function afterLoad(&$results) {
  }

  /**
   * Pre-save hook.
   *
   * This function is invoked before the items will be saved into the DB.
   *
   * @param $item
   *  The array which will be injected to the DB.
   * @param EntityBase $entity
   *  The object of the current item. Used for getting meta tag about the
   *  current entity.
   */
  public function beforeSave(&$item, EntityBase $entity) {
  }

  /**
   * Post-save hook.
   *
   * This function will be invoked after the items returned saved into the DB.
   *
   * @param $item
   *  The array which injected to the DB.
   * @param EntityBase $entity
   *  The object of the current item. Used for getting meta tag about the
   *  current entity.
   */
  public function afterSave(&$item, EntityBase $entity) {
  }

  /**
   * Validating the object before saving it to the DB.
   *
   * The validations are defined in the constraints in the annotation.
   *
   * @param bool $return_errors
   *  Determine if the errors need to return or just throw in the wiled. Default
   *  set to FALSE.
   */
  public function validate($return_errors = FALSE) {
    // todo: trigger other validations.
  }

  /**
   * Determine if the current object should be returned based on the operation.
   *
   * @param $op
   *  The operation: create, read, update or delete.
   * @param $item
   *  The item it self.
   *
   * @return bool
   */
  public function access($op, $item) {
    return true;
  }

}
