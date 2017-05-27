<?php

require_once 'vendor/autoload.php';

$db = \Nuntius\Nuntius::getDb();

//$this->db
//  ->getTable('running_context')
//  ->filter(\r\row('user')->eq($this->data['user']))
//  ->run($this->db->getConnection())
//  ->toArray();

$rows = $db->getQuery()
  ->table('context')
  ->condition('user', 'U368GNEUA')
  ->execute();

