<?php

namespace Nuntius\System;

use Nuntius\Db\DbDispatcher;
use Symfony\Component\Validator\Validation;

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
   * @var \Symfony\Component\Validator\Validator\ValidatorInterface
   */
  protected $validator;

  /**
   * EntityBase constructor.
   * @param DbDispatcher $db
   * @param HooksDispatcherInterface $hooks_dispatcher
   */
  public function __construct(DbDispatcher $db, HooksDispatcherInterface $hooks_dispatcher) {
    $this->dbDispatcher = $db;
    $this->storage = $db->getStorage();
    $this->hooksDispatcher = $hooks_dispatcher;
    $this->validator = Validation::createValidator();
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

    foreach ($this->storage->table($this->plugin_id)->loadMultiple($ids) as $result) {

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
  public function save() {

    if (!empty($this->id)) {
      return $this->update();
    }

    $this->validate();

    $this->beforeSave($this);
    $results = $this->storage->table($this->plugin_id)->save($this->createItem());
    $this->afterSave($this->createInstance($results));
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function delete($id) {
    $this->beforeDelete($this);
    $this->storage->table($this->plugin_id)->delete($id);
    $this->afterDelete($this);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteMultiple(array $ids = []) {
    $this->storage->table($this->plugin_id)->delete($ids);
  }

  /**
   * {@inheritdoc}
   */
  public function update() {
    $this->validate();

    $this->beforeUpdate($this);
    $results = $this->storage->table($this->plugin_id)->update($this->createItem());
    $instance = $this->createInstance($results);
    $this->afterUpdate($instance);

    return $instance;
  }

  /**
   * Creating an item which will be inserted to the DB.
   */
  protected function createItem() {
    $this->beforeCreate($this);
    $item = [];

    foreach ($this->properties as $property) {
      if ($property == 'id' && empty($this->id)) {
        continue;
      }
      $item[$property] = $this->{$property};
    }

    $this->afterCreate($item);

    return $item;
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
   * @param EntityBase $entity
   *  The object of the current item. Used for getting meta tag about the
   *  current entity.
   */
  public function beforeSave(EntityBase $entity) {
  }

  /**
   * Post-save hook.
   *
   * This function will be invoked after the items returned saved into the DB.
   *
   * @param EntityBase $entity
   *  The object of the current item. Used for getting meta tag about the
   *  current entity.
   */
  public function afterSave(EntityBase $entity) {
  }

  /**
   * Pre create hook.
   *
   * This function invoked before the create item function. The create method
   * takes the properties on the object and creates an array which match to the
   * entity properties. For example:
   * $this->foo = 'bar';
   * $this->bar = 'foo';
   * $this->time = 123456789;
   *
   * will be convert to:
   * [
   *  'foo' => 'bar',
   *  'bar' => 'foo',
   *  'time' => 12345789,
   * ]
   *
   * @param EntityBase $entity
   *  The current entity instance.
   */
  public function beforeCreate(EntityBase $entity) {

  }

  /**
   * Post create hook.
   *
   * After the create method created the array which will be the record this
   * method will be invoked. This method allow you to alter records just before
   * the entry will go to the DB.
   *
   * @param array $data
   *  The record.
   */
  public function afterCreate(array &$data) {
    
  }

  /**
   * Pre-update hook.
   *
   * This method will be invoked just before the update of the entity.
   *
   * @param EntityBase $entity
   *
   * @see EntityBase::beforeSave()
   */
  public function beforeUpdate(EntityBase $entity) {
  }

  /**
   * Post update hook.
   *
   * This method will be invoked after the record saved to the DB.
   *
   * @param EntityBase $entity
   *
   * @see EntityBase::afterSave()
   */
  public function afterUpdate(EntityBase $entity) {
  }

  /**
   * Pre delete hook.
   *
   * This method invoked before the entity will be deleted.
   *
   * @param EntityBase $entityBase
   *  The entity object.
   */
  public function beforeDelete(EntityBase $entityBase) {
  }

  /**
   * Post delete hook.
   *
   * The method is invoked after the entity has been deleted.
   *
   * @param EntityBase $entityBase
   *  The entity object.
   */
  public function afterDelete(EntityBase $entityBase) {

  }

  /**
   * Validating the object before saving it to the DB.
   *
   * The validations are defined in the constraints in the annotation.
   *
   * @param bool $return_errors
   *  Determine if the errors need to return or just throw in the wiled. Default
   *  set to FALSE.
   *
   * @return array
   * @throws \Exception
   */
  public function validate($return_errors = FALSE) {
    $constrains = $this->constraints();

    $all = [];

    foreach ($constrains as $property => $constrain) {
      $errors = $this->validator->validate($this->{$property}, $constrains[$property]);

      foreach ($errors as $error) {
        $all[$property][] = $error->getMessage();
      }
    }

    if ($return_errors) {
      return $all;
    }

    if (!$all) {
      return [];
    }

    $message = [];

    foreach ($all as $key => $errors) {
      $message[] = "{$key}: " . implode(", ", $errors);
    }

    throw new \Exception(implode("\n", $message));
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

  /**
   * Installing the table.
   */
  public function installEntity() {
    $this->dbDispatcher->getOperations()->tableCreate($this->plugin_id);
  }


  /**
   * Return a list of constraints.
   *
   * @return array
   *  The array is constructed by:
   *  [
   *    'property' => [
   *      new \ConstraintObject()
   *    ],
   *  ]
   */
  protected function constraints() {
    return [];
  }

}
