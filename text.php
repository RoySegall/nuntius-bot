<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$buttons_template = $facebook
  ->templates
  ->list;

$buttons_template->addElement(
  $facebook->templates
    ->element
    ->title('Be with here ep1')
    ->subtitle('Just be with her')
    ->imageUrl('https://scontent-frx5-1.xx.fbcdn.net/v/t1.0-1/p200x200/18275196_1390027701040210_3878564942831723890_n.jpg?oh=76b58c6f23c9267e32def90779d5aaed&oe=599CADC1')
    ->addButton($facebook->buttons->url->url('https://mako.co.il')->title('view'))
);

$buttons_template->addElement(
  $facebook->templates
    ->element
    ->title('Be with here ep2')
    ->subtitle('Just be with her')
    ->resetButtons()
    ->addButton($facebook->buttons->url->url('https://mako.co.il')->title('view'))
);

$buttons_template->addButton(
  $facebook->buttons->postBack->title('load more')->payload('load more')
);
Kint::dump($buttons_template->getData());
