<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$buttons_template = $facebook
  ->templates
  ->receipt;

$buttons_template
  ->merchantName('Ovad')
  ->recipientName('Roy Segall')
  ->currency('ILS')
  ->paymentMethod('cache')
  ->orderNumber('mako.co.il')
  ->totalCost('30')
  ->addElement(
    $facebook->templates->receiptElement
      ->title('Sabich')
      ->subtitle('Salad, Egg, Eggplant, Hummos')
      ->price(25)
      ->quantity(1)
      ->currency('ILS')
      ->imageUrl('https://images1.ynet.co.il/PicServer2/13062011/3366449/2wa.jpg')
  )
  ->addElement(
    $facebook->templates->receiptElement
      ->reset()
      ->title('Grapes')
      ->price(5)
      ->quantity(1)
      ->currency('ILS')
      ->imageUrl('http://www.burgerking.co.il/Uploads/Product%20Images/GrapeJuiceBottle.jpg')
  )
  ->street1('Harav levin')
  ->city('Ramat gan')
  ->state('Israel')
  ->country('IL')
  ->postalCode('52260')
  ->addAdjustment('Hate eggplant', 20)
  ->addAdjustment('First timer', 10);

Kint::dump($buttons_template->getData());
