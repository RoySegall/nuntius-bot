<?php

namespace Nuntius\Dispatcher;

use Nuntius\NuntiusEventDispatchSetDataTrait;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event object with information about the PR/issue.
 */
class GitHubEvent extends Event {

  use NuntiusEventDispatchSetDataTrait;

}
