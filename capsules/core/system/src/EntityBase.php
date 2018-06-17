<?php

namespace Nuntius\System;

use Nuntius\Db\DbDispatcher;
use Symfony\Component\Validator\Validation;

/**
 * Class EntityBase
 * @package Nuntius\System
 */
abstract class EntityBase implements HookContainerInterface {

  const SINGLE = 'single';

  const MANY = 'many';

  public $plugin_id;

  public $properties;

  public $relations;

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
   * @var EntityPluginManager
   */
  protected $entityPluginManager;

  /**
   * EntityBase constructor.
   * @param DbDispatcher $db
   * @param HooksDispatcherInterface $hooks_dispatcher
   * @param EntityPluginManager $entity_plugin_manager
   */
  public function __construct(DbDispatcher $db, HooksDispatcherInterface $hooks_dispatcher, EntityPluginManager $entity_plugin_manager) {
    $this->dbDispatcher = $db;
    $this->storage = $db->getStorage();
    $this->hooksDispatcher = $hooks_dispatcher;
    $this->entityPluginManager = $entity_plugin_manager;

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
    return new static($container->get('db'), $container->get('hooks_dispatcher'), $container->get('entity.plugin_manager'));
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
   * @param bool $load_relations
   *   Determine if we need to load relations or keep it as a simple record.
   *
   * @return $this
   *   The current object.
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  protected function createInstance($data, $load_relations = TRUE) {
    $this_copy = clone $this;

    foreach ($data as $property => $value) {

      if ($load_relations && !empty($this->relations) && in_array($property, array_keys($this->relations))) {
        $relationship = $this->relations[$property];

        /** @var EntityBase $entity_manager */
        $entity_manager = $this->entityPluginManager->createInstance($relationship['id']);

        if ($relationship['type'] == self::MANY) {
          $value = $entity_manager->loadMultiple($value);
        }

        if ($relationship['type'] == self::SINGLE) {
          $value = $entity_manager->load($value);
        }
      }
      $this_copy->{$property} = $value;
    }

    return $this_copy;
  }

  /**
   * {@inheritdoc}
   */
  public function loadMultiple(array $ids = [], $load_relations = TRUE) {
    $results = [];

    foreach ($this->storage->table($this->plugin_id)->loadMultiple($ids) as $result) {

      if (!$this->access('view', $result)) {
        continue;
      }

      $results[$result['id']] = $this->createInstance($result, $load_relations);
    }

    $this->hookLoad($results);

    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function load($id, $load_relations = TRUE) {
    $results = $this->loadMultiple([$id], $load_relations);
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

    $results = $this->storage->table($this->plugin_id)->save($this->createItem());

    $new_results = $this->createInstance($results);
    $this->hookSave($new_results);

    return $new_results;
  }

  /**
   * {@inheritdoc}
   */
  public function delete($id) {
    // todo: use the current id.
    $this->storage->table($this->plugin_id)->delete($id);
    $this->hookDelete($this);
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

    $results = $this->storage->table($this->plugin_id)->update($this->createItem());
    $instance = $this->createInstance($results);
    $this->hookUpdate($instance);

    return $instance;
  }

  /**
   * Creating an item which will be inserted to the DB.
   */
  protected function createItem() {
    $this->hookCreate($this);
    $item = [];

    foreach ($this->properties as $property) {
      if ($property == 'id' && empty($this->id)) {
        continue;
      }
      $item[$property] = $this->{$property};
    }

    return $item;
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
  public function hookLoad(&$results) {
    $args = ['entities' => &$results];
    $this->hooksHelper($args, 'load');

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
  public function hookSave(EntityBase $entity) {
    $args = ['entity' => &$entity];
    $this->hooksHelper($args, 'save');
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
  public function hookCreate(EntityBase $entity) {
    $args = ['entity' => &$entity];
    $this->hooksHelper($args, 'create');
  }

  /**
   * Post update hook.
   *
   * This method will be invoked after the record saved to the DB.
   *
   * @param EntityBase $entity
   *
   * @see EntityBase::hookSave()
   */
  public function hookUpdate(EntityBase $entity) {
    $args = ['entity' => &$entity];
    $this->hooksHelper($args, 'update');
  }

  /**
   * Post delete hook.
   *
   * The method is invoked after the entity has been deleted.
   *
   * @param EntityBase $entity
   *  The entity object.
   */
  public function hookDelete(EntityBase $entity) {
    $args = ['entity' => &$entity];
    $this->hooksHelper($args, 'delete');
  }

  /**
   * Hooks trigger helper function.
   *
   * @param $args
   *  The args which will pass to the hooks.
   * @param $hook
   *  The name of the hook.
   */
  protected function hooksHelper(&$args, $hook) {
    $this
      ->hooksDispatcher
      ->setArguments($args)
      ->alter('entity_' . $hook);

    $this
      ->hooksDispatcher
      ->setArguments($args)
      ->alter($this->plugin_id . '_' . $hook);
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

    // Checking relations.
    $this->validateRelationships($all);

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
   * Make sure the relationships are valid.
   *
   * @param $all
   *  The relationships.
   * @throws \Nuntius\Capsule\CapsuleErrorException
   */
  protected function validateRelationships(&$all) {
    if (empty($this->relations)) {
      return;
    }

    foreach ($this->relations as $relation => $info) {
      $value = $this->{$relation};
      $is_multiple = is_array($value);

      /** @var EntityBase $entity */
      $entity = $this->entityPluginManager->createInstance($info['id']);

      if ($info['type'] == 'single') {
        // Check that this is a single item.

        if ($is_multiple) {
          $all[$relation][] = 'The relation type is single thus cannot be a multiple list of IDs';
        }
        else {
          if (!$entity->load($this->{$relation})) {
            $all[$relation][] = 'There is no matching ' . $info['id'] . ' entity with the ID ' . $this->{$relation};
          }
        }
      }

      if ($info['type'] == self::MANY) {
        // Check that this is a multiple items.
        if (!$is_multiple) {
          $all[$relation][] = 'The relation type is multiple thus cannot be a single ID';
        }
        else {
          // Load all the entities.
          $entities = $entity->loadMultiple($value);

          // Check the entities which not been loaded from the DB and add them
          // to the errors list.
          if ($keys = array_diff($value, array_keys($entities))) {
            $all[$relation][] = 'The IDs: ' . implode(', ', $keys) . ' are not a valid IDs for the entity ' . $this->plugin_id;
          }
        }
      }
    }
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
    // todo: create entity test service.
    return true;
  }

  /**
   * Installing the table.
   */
  public function installEntity() {
    if ($this->dbDispatcher->getOperations()->tableExists($this->plugin_id)) {
      return;
    }

    $this->dbDispatcher->getOperations()->tableCreate($this->plugin_id);
  }

  /**
   * Return a list of constraints.
   *
   * @return array
   *  The array is constructed by:
   *  [
   *    'property' => [
   *      new \ConstraintObject(),
   *    ],
   *  ]
   */
  protected function constraints() {
    return [];
  }

}
