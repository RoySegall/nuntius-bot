<?php

namespace Nuntius;

use Symfony\Component\Yaml\Yaml;

/**
 * Config manager.
 */
class NuntiusConfig {

  /**
   * The combined settings.
   *
   * @var array
   */
  protected $settings = [];

  /**
   * Constructing the nuntius config service.
   */
  public function __construct() {
    $this->settings =  array_merge($this->getHooks(), $this->getCredentials());
  }

  /**
   * Get all the hooks.
   *
   * @return array
   *   List of the hooks.
   */
  public function getHooks() {
    return $this->concatYmlFiles('hooks');
  }

  /**
   * Return list of credentials.
   *
   * @return array
   *   Credentials to the DB, 3rd party services etc. etc.
   */
  public function getCredentials() {
    return $this->concatYmlFiles('credentials');
  }

  /**
   * credentials a settings file with the local one.
   *
   * Settings(hooks and credentials) are listed inside a X.settings.yml or
   * X.local.yml. The non-local yml contains settings provided by Nuntius core
   * while the local yml file intend to override default settings.
   *
   * The method will combine both of the settings into a single array while the
   * local settings file is the one which will call the shot.
   *
   * @param $file
   *   The type of file i.e credentials or hooks.
   * @return array
   */
  protected function concatYmlFiles($file) {
    $path = __DIR__ . '/../settings/';
    $main_settings = Yaml::parse(file_get_contents($path . $file . '.yml'));

    if (file_exists($path . $file . '.local.yml')) {
      $local_settings = Yaml::parse(file_get_contents($path . $file . '.local.yml'));
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
   * The merged settings.
   *
   * @return array
   *   The merged settings.
   */
  public function getSettings() {
    return $this->settings;
  }

  /**
   * Get a specific setting.
   *
   * @param string $key
   *   The name of the setting.
   *
   * @return mixed
   *   The value of the setting.
   */
  public function getSetting($key) {
    return $this->settings[$key];
  }

}
