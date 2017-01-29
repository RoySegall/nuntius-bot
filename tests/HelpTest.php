<?php

namespace tests;

class HelpTest extends TestsAbstract {

  /**
   * Testing the help request.
   *
   * @covers Help::help()
   */
  public function testHelp() {
    
    $sentences = [
      'help',
      'Help',
      '-help',
      '--help',
      'h',
      '-h',
      '--h',
      'What can you do?',
      'What can you do',
    ];

    $this->nuntius->getPlugins();
    $categories = $this->getCategories();

    $results = [
      "There is a list of things I can do.",
      "It is sorted by categories: " . implode(", ", $categories) . '.',
      "Just ask me: `@nuntius what can you do in ...`",
    ];

    foreach ($sentences as $sentence) {
      $this->assertEquals($this->nuntius->getPlugin($sentence), $results);
    }
  }

  /**
   * Testing the documentation for each plugin.
   *
   * @covers Help::actionsIn()
   */
  public function testWhatCanYouDoIn() {
    foreach ($this->nuntius->getPlugins() as $plugin) {

      if (!$plugin->getCategory()) {
        continue;
      }

      $results = $this->nuntius->getPlugin('@nuntius what can you do in ' . $plugin->getCategory());

      $documentation = ['I can do cool stuff with ' . $plugin->getCategory() . ': '];
      foreach ($plugin->formats as $format) {
        $documentation[] = "`{$format['human_command']}`: {$format['description']}";
      }

      $this->assertEquals($documentation, $results);

    }

  }

  /**
   * Return list of the categories.
   *
   * @return array
   */
  protected function getCategories() {
    $categories = [];

    foreach ($this->nuntius->getPlugins() as $plugin) {

      if (!$plugin->getCategory()) {
        continue;
      }

      $categories[] = $plugin->getCategory();
    }

    return $categories;
  }

}
