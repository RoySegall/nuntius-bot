<?php

namespace Nuntius;
use Slack\RealTimeClient;

/**
 * Class NuntiusPluginAbstract.
 *
 * Base class for all the plugins.
 */
abstract class NuntiusPluginAbstract {

  use NuntiusServicesTrait;

  /**
   * @var \Nuntius\NuntiusRethinkdb
   */
  protected $db;

  /**
   * @var \Slack\RealTimeClient
   */
  protected $client;

  /**
   * The entity manager.
   *
   * @var \Nuntius\EntityManager
   */
  protected $entityManager;

  /**
   * Information about the action.
   *
   * @var array
   */
  public $data;

  /**
   * Constructing the class.
   *
   * @param \Slack\RealTimeClient $client
   *   The client object.
   */
  function __construct(RealTimeClient $client) {
    $this->db = Nuntius::getRethinkDB();
    $this->client = $client;
    $this->entityManager = Nuntius::getEntityManager();
  }

  /**
   * The action to commit when the event on slack is triggered.
   */
  abstract public function action();

  /**
   * Invoking an action before triggering the action method.
   */
  public function preAction() {
  }

  /**
   * Invoking an action after the action method was triggered.
   */
  public function postAction() {
  }

}
