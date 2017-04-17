<?php

namespace Nuntius;

/**
 * Interface UpdateBaseInterface.
 */
interface UpdateBaseInterface {

  /**
   * Describe what the update going to do.
   *
   * @return string
   *   What the update going to do.
   */
  public function description();

  /**
   * Running the update.
   *
   * @return string
   *   A message for what the update did.
   */
  public function update();

}
