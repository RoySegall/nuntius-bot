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
    '/what can you do in (.*)/' => [
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

      if (!$plugin->getCategory()) {
        continue;
      }

      $categories[] = $plugin->getCategory();
    }

    return [
      "There is a list of things I can do.",
      "It is sorted by categories: " . implode(", ", $categories) . '.',
      "Just ask me: `@nuntius what can you do in ...`",
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
