<?php

namespace Nuntius\Plugins;

use Nuntius\NuntiusPluginAbstract;

class Help extends NuntiusPluginAbstract {

  public $formats = [
    'help,-help,--help,h,-h,--h,Help,What can you do?,What can you do' => [
      'callback' => 'help',
      'description' => '',
    ],
    '/what can do in (.*)/' => [
      'callback' => 'actionsIn',
      'description' => '',
    ],
  ];

  /**
   * All the stuff that nuntius can do.
   */
  public function help() {
    // todo: Get categories.
    $categories = [];

    return [
      "There is a list of things that I can do.",
      "It sorted by categories: " . $categories,
      "If you'ld like to know, send me: `@nuntius what can do in ...`",
    ];
  }

  /**
   * List actions in a specific category.
   *
   * @param $category
   *   The category name.
   */
  public function actionsIn($category) {

  }

}
