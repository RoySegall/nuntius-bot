<?php

namespace Nuntius\Plugins;

use Nuntius\NuntiusPluginAbstract;

class Help extends NuntiusPluginAbstract {

  public $formats = [
    'help,help,-help,--help,h,-h,--h,Help,What can you do?,What can you do' => [
      'callback' => 'help',
      'description' => "Help: you can trigger it with one of the above: 
      `help,help,-help,--help,h,-h,--h,Help,What can you do?,What can you do` and i'll show you all the commands.",
    ],
    '/what can do in (.*)/' => [
      'callback' => 'actionsIn',
      'description' => 'Actions: Ask action in a category by 
      `what can do in foo` and you will see the commands in a category',
    ],
  ];

  /**
   * All the stuff that nuntius can do.
   */
  public function help() {
    $categories = [];

    foreach ($this->plugins as $plugin) {

      if (!$plugin) {
        continue;
      }

      $categories[] = $plugin->getCategory();
    }

    return [
      "There is a list of things that I can do.",
      "It sorted by categories: " . implode(", ", $categories),
      "If you'ld like to know, send me: `@nuntius what can do in ...`",
    ];
  }

  /**
   * List actions in a specific category.
   *
   * @param $category
   *   The category name.
   * @return string
   */
  public function actionsIn($category) {
    $message = ['I can do cool stuff with ' . $category . ': '];
    foreach ($this->plugins as $plugin) {

      if ($plugin->getCategory() != $category) {
        continue;
      }

      foreach ($plugin->formats as $format) {
        $message[] = '`' . $format['human_command'] . '`: ' . $format['description'];
      }
    }

    return $message;
  }

}
