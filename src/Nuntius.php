<?php

namespace Nuntius;

use Symfony\Component\Yaml\Yaml;

class Nuntius {

  /**
   * Getting the settings.
   *
   * @return array
   */
  public static function getSettings() {
    $main_settings = Yaml::parse(file_get_contents('settings.yml'));

    $local_settings = [];
    if (file_exists('settings.local.yml')) {
      $local_settings = Yaml::parse(file_get_contents('settings.local.yml'));
    }

    $settings = [];
    foreach ($main_settings as $key => $value) {
      // Getting settings from the main default settings.
      $settings[$key] = $main_settings[$key];

      if (is_array($settings[$key])) {
        // The setting is defined as settings. Merge the local settings with the
        // main settings.
        if (isset($local_settings[$key])) {
          $settings[$key] = $local_settings[$key] + $main_settings[$key];
        }
      }
      else {
        // The setting is not an array(bot access token) - the local settings
        // call the shot for the value of the setting.
        $settings[$key] = isset($local_settings[$key]) ? $local_settings[$key] : '';
      }

    }

    return $settings;
  }

  /**
   * Get the DB instance.
   *
   * @return NuntiusRethinkdb
   */
  public static function getRethinkDB() {
    return new NuntiusRethinkdb(self::getSettings()['rethinkdb']);
  }

  /**
   * Get the entity manager.
   *
   * @return EntityManager
   *   The entity manager.
   */
  public static function getEntityManager() {
    $entities = self::getSettings()['entities'];

    return new EntityManager($entities);
  }

  /**
   * Get the task manager.
   *
   * @return \Nuntius\TasksManager
   *   The task manager object.
   */
  public static function getTasksManager() {
    $tasks = self::getSettings()['tasks'];

    return new TasksManager($tasks);
  }

  /**
   * Get the update manager.
   *
   * @return \Nuntius\UpdateManager
   */
  public static function getUpdateManager() {
    $updates = self::getSettings()['updates'];

    return new UpdateManager($updates);
  }

}
