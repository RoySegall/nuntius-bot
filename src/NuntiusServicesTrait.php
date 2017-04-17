<?php

namespace Nuntius;

/**
 * Contains shared logic.
 */
trait NuntiusServicesTrait {

  /**
   * Check if a text is matching to a text templates.
   *
   * In case the text is matching to the template the arguments will be
   * exported.
   *
   * The sentence should be in the format of:
   * @code
   *  /what can you do in (.*)/
   * @endcode
   *
   * @param $input
   *   The text the user submitted.
   * @param $template
   *   The format of the plugin.
   *
   * @return boolean|array
   *   In case there is not match, return FALSE. If found, return the arguments
   *   from the sentence.
   */
  public function matchTemplate($input, $template) {
    if (!preg_match($template, $input, $matches)) {
      return FALSE;
    }

    if (count($matches) == 1) {
      return TRUE;
    }

    unset($matches[0]);

    return $matches;
  }

}
