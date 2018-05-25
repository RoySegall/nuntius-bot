<?php

namespace Nuntius\System;

interface HookInterface {

  public function invoke();

  public function alter();

};
