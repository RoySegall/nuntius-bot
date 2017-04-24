<?php

namespace Nuntius;

use Symfony\Component\Yaml\Yaml;

class NuntiusConfig {

  /**
   * Settings from the main settings.yml file.
   *
   * @var array
   */
  protected $mainSettings = [];

  /**
   * Settings from the settings.local.yml file.
   *
   * @var array
   */
  protected $localSettings = [];

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
    $this->mainSettings = Yaml::parse(file_get_contents('settings.yml'));

    if (file_exists('settings.local.yml')) {
      $this->localSettings = Yaml::parse(file_get_contents('settings.local.yml'));
    }

    foreach ($this->mainSettings as $key => $value) {
      // Getting settings from the main default settings.
      $this->settings[$key] = $this->mainSettings[$key];

      if (is_array($this->settings[$key])) {
        // The setting is defined as settings. Merge the local settings with the
        // main settings.
        if (isset($this->localSettings[$key])) {
          $this->settings[$key] = $this->localSettings[$key] + $this->mainSettings[$key];
        }
      }
      else {
        // The setting is not an array(bot access token) - the local settings
        // call the shot for the value of the setting.
        $this->settings[$key] = isset($this->localSettings[$key]) ? $this->localSettings[$key] : '';
      }
    }
  }

  /**
   * Return the local settings.
   *
   * @return array
   *   Local settings.
   */
  public function getLocalSettings() {
    return $this->localSettings;
  }

  /**
   * Return the main settings.
   *
   * @return array
   *  Main settings.
   */
  public function getMainSettings() {
    return $this->mainSettings;
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
