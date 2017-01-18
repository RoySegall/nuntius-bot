<?php

namespace Nuntius;

abstract class NuntiusPluginAbstract implements NuntiusPluginInterface {

  /**
   * @var string
   */
  protected $category;

  /**
   * Holds all the operation the plugin can handle.
   *
   * @var array
   */
  public $formats;

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
   * @var NuntiusPluginAbstract[]
   */
  protected $plugins;

  /**
   * Setting some context for the operation.
   *
   * @param $author
   *   The author of the message for the bot.
   *
   * @return NuntiusPluginInterface
   */
  public function setAuthor($author) {
    $this->author = $author;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFormats() {
    return $this->formats;
  }

  /**
   * @param mixed $formats
   *
   * @return NuntiusPluginInterface
   */
  public function setFormats($formats) {
    $this->formats = $formats;

    return $this;
  }

  /**
   * @param NuntiusPluginAbstract[] $plugins
   */
  public function setPlugins(array $plugins) {
    $this->plugins = $plugins;
  }

  /**
   * @return string
   */
  public function getCategory() {
    return $this->category;
  }

}
