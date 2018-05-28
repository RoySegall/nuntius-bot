<?php

namespace Nuntius\System;

interface HookInterface {

  public function invoke($arguments);

  public function alter(&$arguments);

};
