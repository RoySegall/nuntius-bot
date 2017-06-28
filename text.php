<?php

require_once 'vendor/autoload.php';

$rows = \Nuntius\Nuntius::getDb()->getQuery()->table('fb_reminders')->condition('recipient_id', 129)->execute();

if (!$rows) {
  \Nuntius\Nuntius::getDb()->getStorage()->table('fb_reminders')->save(['recipient_id' => 129]);
}
else {
  \Nuntius\Nuntius::getEntityManager()->get('fb_reminders')->delete($rows[0]['id']);
}

Kint::dump($rows);