<?php

namespace Nuntius;

use React\EventLoop\StreamSelectLoop;
use Slack\RealTimeClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use React\EventLoop\Factory;
use Symfony\Component\DependencyInjection\Reference;

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
   *   The Nuntius config service.
   *
   * @throws \Exception
   */
  public static function getSettings() {
    return self::container()->get('config');
  }

  /**
   * Get the container.
   *
   * @param null $reset
   *  A flag which control if we want to reload the services.
   *
   * @return ContainerBuilder
   *   The container object.
   *
   * @throws \Exception
   */
  public static function container($reset = NULL) {
    static $container;

    if ($reset) {
      $container = NULL;
    }

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

    try {
      $capsule_manager = $container->get('capsule_manager');

      foreach ($capsule_manager->getCapsulesForBootstrapping() as $capsule) {
        $capsule_services = $capsule_manager->getCapsuleImplementations($capsule['machine_name'], 'services');

        foreach ($capsule_services as $id => $capsule_service) {
          if (!empty($capsule_service['arguments'])) {
            $capsule_service['arguments'] = array_map(function($item) {
              return new Reference(str_replace('@', '', $item));
            }, $capsule_service['arguments']);
          }
          else {
            $capsule_service['arguments'] = [];
          }
          $container
            ->register($id, $capsule_service['class'])
            ->setArguments($capsule_service['arguments']);
        }
      }
    } catch (\Exception $e) {

    }

    return $container;
  }

  /**
   * Get the DB instance.
   *
   * @return NuntiusRethinkdb
   *
   * @throws \Exception
   *
   * @internal
   */
  public static function getRethinkDB() {
    return self::container()->get('rethinkdb');
  }

  /**
   * Get the MongoDB dispatcher.
   *
   * @return NuntiusMongoDB
   *
   * @throws \Exception
   * @internal
   */
  public static function getMongoDB() {
    return self::container()->get('mongodb');
  }

  /**
   * Get the entity manager.
   *
   * @return EntityManager
   *   The entity manager.
   *
   * @throws \Exception
   */
  public static function getEntityManager() {
    return self::container()->get('manager.entity');
  }

  /**
   * Get the task manager.
   *
   * @return \Nuntius\TasksManager
   *   The task manager object.
   *
   * @throws \Exception
   */
  public static function getTasksManager() {
    return self::container()->get('manager.task');
  }

  /**
   * Get the cron task manager.
   *
   * @return CronManager
   *   The cron manager.
   *
   * @throws \Exception
   */
  public static function getCronTasksManager() {
    return self::container()->get('manager.cron');
  }

  /**
   * Get the update manager.
   *
   * @return \Nuntius\UpdateManager
   *   The update manager.
   *
   * @throws \Exception
   */
  public static function getUpdateManager() {
    return self::container()->get('manager.update');
  }

  /**
   * Return nuntius dispatcher object..
   *
   * @return \Nuntius\NuntiusDispatcher
   *   Nuntius dispatcher manager.
   *
   * @throws \Exception
   */
  public static function getDispatcher() {
    return self::container()->get('dispatcher')->buildDispatcher();
  }

  /**
   * Return the guzzle component.
   *
   * @return \GuzzleHttp\Client
   *
   * @throws \Exception
   */
  public static function getGuzzle() {
    return self::container()->get('http');
  }

  /**
   * Get the DB layer manager.
   *
   * @return \Nuntius\Db\DbDispatcher
   *
   * @throws \Exception
   */
  public static function getDb() {
    return self::container()->get('db');
  }

  /**
   * Get the context manager.
   *
   * @return ContextManager
   *
   * @throws \Exception
   */
  public static function getContextManager() {
    return self::container()->get('context');
  }

  /**
   * Get the context manager.
   *
   * @return \FacebookMessengerSendApi\SendAPI
   *
   * @throws \Exception
   */
  public static function facebookSendApi() {
    return self::container()->get('facebook_send_api');
  }

  /**
   * Get FB postback manager.
   *
   * @return \Nuntius\FbPostBackManager
   *
   * @throws \Exception
   */
  public static function getFbPostBackManager() {
    return self::container()->get('manager.fb_postback');
  }

  /**
   * @return \Nuntius\Capsule\CapsuleServiceInterface
   *
   * @throws \Exception
   */
  public static function getCapsuleManager() {
      return self::container()->get('capsule_manager');
  }

  /**
   * Registering commands.
   *
   * @param \Symfony\Component\Console\Application $application
   *
   * @throws \Exception
   */
  public static function addCommands(Application $application) {

    $commands = self::getSettings()->getSetting('commands');
    foreach ($commands as $namespace) {
      $application->add(new $namespace);
    }

    // Register other capsules commands. Wrapping it with a try in case the
    // system is not installed.
    try {
      $capsule_manager = self::getCapsuleManager();

      foreach ($capsule_manager->getCapsulesForBootstrapping() as $capsule) {
        $services = $capsule_manager->getCapsuleImplementations($capsule['machine_name'], 'services');
        foreach ($services as $id => $service) {
          if (empty($service['command'])) {
            continue;
          }

          $application->add(self::container()->get($id));
        }

      }
    } catch (\Exception $e) {

    }
  }

}
