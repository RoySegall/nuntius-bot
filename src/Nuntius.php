<?php

namespace Nuntius;

use React\EventLoop\StreamSelectLoop;
use Slack\RealTimeClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use React\EventLoop\Factory;

/**
 * Class for everything. Responsible for bootstrapping, getting the container
 * and aliasing for all the managers which accessible through the container.
 */
class Nuntius {

  /**
   * @var StreamSelectLoop
   */
  protected static $client_loop;

  /**
   * Bootstrapping the slack bot.
   *
   * @return RealTimeClient
   *   The client object.
   *
   * @throws \Exception
   */
  public static function bootstrap() {
    $token = self::getSettings()->getSetting('access_token');

    if (empty($token)) {
      throw new \Exception('The access token is missing');
    }

    // Set up stuff.
    self::$client_loop = Factory::create();

    $client = new RealTimeClient(self::$client_loop);
    $client->setToken($token);

    return $client;
  }

  /**
   * Run the boot.
   */
  public static function run() {
    self::$client_loop->run();
  }

  /**
   * Getting the settings.
   *
   * @return NuntiusConfig
   *   The nuntius config service.
   */
  public static function getSettings() {
    return self::container()->get('config');
  }

  /**
   * Get the container.
   *
   * @return ContainerBuilder
   *   The container object.
   */
  public static function container() {
    static $container;

    if ($container) {
      // We already got the container, return that.
      return $container;
    }

    $container = new ContainerBuilder();
    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../settings/'));

    $loader->load('services.yml');

    // Load all the services files.
    foreach (self::getSettings()->getSetting('services') as $service) {
      $loader->load($service);
    }

    return $container;
  }

  /**
   * Get the DB instance.
   *
   * @return NuntiusRethinkdb
   */
  public static function getRethinkDB() {
    return self::container()->get('rethinkdb');
  }

  /**
   * Get the entity manager.
   *
   * @return EntityManager
   *   The entity manager.
   */
  public static function getEntityManager() {
    return self::container()->get('manager.entity');
  }

  /**
   * Get the task manager.
   *
   * @return \Nuntius\TasksManager
   *   The task manager object.
   */
  public static function getTasksManager() {
    return self::container()->get('manager.task');
  }

  /**
   * Get the cron task manager.
   *
   * @return CronManager
   *   The cron manager.
   */
  public static function getCronTasksManager() {
    return self::container()->get('manager.cron');
  }

  /**
   * Get the update manager.
   *
   * @return \Nuntius\UpdateManager
   *   The update manager.
   */
  public static function getUpdateManager() {
    return self::container()->get('manager.update');
  }

  /**
   * Return nuntius dispatcher object..
   *
   * @return \Nuntius\NuntiusDispatcher
   *   Nuntius dispatcher manager.
   */
  public static function getDispatcher() {
    return self::container()->get('dispatcher')->buildDispatcher();
  }

}
