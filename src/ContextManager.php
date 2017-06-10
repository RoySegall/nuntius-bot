<?php

namespace Nuntius;

/**
 * Context manager help us to know what is the current context.
 *
 * A can, and should be, the current chat interface - Slack, Facebook etc. etc.
 */
class ContextManager {

  /**
   * The current context.
   *
   * @var string
   */
  protected $context;

  /**
   * Get the context.
   *
   * @return string
   *   The context.
   */
  public function getContext() {
    return $this->context;
  }

  /**
   * Set the context.
   *
   * @param string $context
   *   The current context.
   *
   * @return ContextManager
   *   The current context.
   */
  public function setContext($context) {
    $this->context = $context;

    return $this;
  }

}
