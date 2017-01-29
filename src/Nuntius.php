<?php

namespace Nuntius;

use Nuntius\Plugins\Help;
use Nuntius\Plugins\Reminder;
use Nuntius\Plugins\SmallTalk;
use Symfony\Component\Yaml\Yaml;

class Nuntius {

  /**
   * The path of the plugins.
   *
   * @var NuntiusPluginAbstract[]
   */
  protected $plugins;

  /**
   * Array with some information relate to the operation.
   *
   * @var array
   */
  public $author;

  /**
   * @var NuntiusSuperCommand
   */
  protected $nuntius;

  /**
   * Nuntius constructor.
   */
  function __construct() {
    $this->addPlugins(New Reminder());
    $this->addPlugins(New SmallTalk());
    $this->addPlugins(New Help());
  }

  /**
   * Setting some context for the operation.
   *
   * @param $author
   *   The author of the message for the bot.
   *
   * @return Nuntius
   */
  public function setAuthor($author) {
    $this->author = $author;

    return $this;
  }

  /**
   * Getting the settings.
   *
   * @return array
   */
  public static function getSettings() {
    return Yaml::parse(file_get_contents('settings.yml'));
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
   * @param NuntiusPluginAbstract $plugin
   *
   * @return $this
   */
  public function addPlugins(NuntiusPluginAbstract $plugin) {
    $this->plugins[$plugin->getCategory()] = $plugin;

    return $this;
  }

  /**
   * @return NuntiusPluginAbstract[]
   */
  public function getPlugins() {
    return $this->plugins;
  }

  /**
   * Get the best matching plugin according to the current text.
   *
   * @param $sentence
   *   The text the user submitted.
   *
   * @return string
   *   The text after the actions completed.
   */
  public function getPlugin($sentence) {

    // Remove nuntius mention from the sentence.
    $sentence = trim(str_replace('@nuntius', '', $sentence));

    foreach ($this->plugins as $plugin) {

      foreach ($plugin->formats as $format => $info) {

        if (strpos($format, '/') === FALSE) {
          $list = explode(',', $format);

          if (in_array($sentence, $list)) {
            $arguments = [];
          }
          else {
            continue;
          }
        }
        else {
          $arguments = $this->stepDefinitionMatch($sentence, $format);

          if ($arguments === FALSE) {
            // No matching plugin at all. Skipping.
            continue;
          }
        }

        if ($arguments === TRUE) {
          // We fount a plugin but there is no arguments the callback need. set
          // the arguments as an empty array.
          $arguments = [];
        }

        if (!is_callable([$plugin, $info['callback']])) {
          continue;
        }

        $plugin->setPlugins($this->getPlugins());

        $plugin->setAuthor($this->author);
        if ($text = call_user_func_array([$plugin, $info['callback']], $arguments)) {
          return $text;
        }
      }
    }
  }

  /**
   * Check if the plugin is matching.
   *
   * @param $user_input
   *   The text the user submitted.
   * @param $plugin_format
   *   The format of the plugin.
   *
   * @return boolean|array
   *   In case there is not match, return FALSE. If found, return the artguments
   *   from the sentence.
   */
  public function stepDefinitionMatch($user_input, $plugin_format) {
    if (!preg_match($plugin_format, $user_input, $matches)) {
      return FALSE;
    }

    if (count($matches) == 1) {
      return TRUE;
    }

    unset($matches[0]);

    return $matches;
  }

}
