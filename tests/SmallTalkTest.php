<?php

namespace tests;

class SmallTalkTest extends TestsAbstract {

  /**
   * Testing the small talk responses.
   *
   * @covers  SmallTalk::Hi()
   */
  public function testHi() {
    $sentences = [
      'hi',
      'Hi',
      'Hey',
      'hey',
      'hello',
      'Hello',
      'Shalom',
      'shalom',
      'hello',
    ];
    $possible = [
      'Hi there!',
      'Hello to you too',
      "Well hello there!",
      "if you gotta shoot shoot don't talk :gun:",
    ];

    foreach ($sentences as $sentence) {
      $this->assertTrue(in_array($this->nuntius->getPlugin($sentence), $possible));
    }
  }
}
