<?php

namespace Nuntius;

class Nuntius {

  /**
   * The path of the plugins.
   *
   * @var NuntiusPlugin[]
   */
  protected $plugins;

  /**
   * @param NuntiusPlugin $plugin
   * @return $this
   */
  public function addPlugins(NuntiusPlugin $plugin) {
    $this->plugins[] = $plugin;

    return $this;
  }

  /**
   * Get the best matching plugin accodring to the current text.
   *
   * @param $sentence
   *   The text the user submitted.
   */
  public function getPlugin($sentence) {

    // Remove nuntius mention from the sentence.
    $sentence = trim(str_replace('@nuntius', '', $sentence));

    foreach ($this->plugins as $plugin) {

    }

  }

  /**
   * Check if the plugin is matching.
   *
   * @param $user_input
   *   The text the user submitted.
   * @param $plugin_format
   *   The format of the plugin.
   */
  public function matchPlugin($user_input, $plugin_format) {

  }

}
