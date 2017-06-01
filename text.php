<?php

require_once 'vendor/autoload.php';

Kint::dump(\Nuntius\Nuntius::getDb()->getQuery()->table('superheroes')->execute());