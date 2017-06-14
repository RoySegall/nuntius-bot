<?php

require_once 'vendor/autoload.php';

$facebook = new \Nuntius\FacebookSendApi\SendAPI();

$buttons_template = $facebook
  ->templates
  ->generic;

$buttons_template->addElement(
  $facebook->templates
    ->element
    ->title('Be with here')
    ->subtitle('Just be with her')
    ->imageUrl('https://scontent-frx5-1.xx.fbcdn.net/v/t1.0-1/p200x200/18275196_1390027701040210_3878564942831723890_n.jpg?oh=76b58c6f23c9267e32def90779d5aaed&oe=599CADC1')
    ->addButton($facebook->buttons->share)
    ->addButton($facebook->buttons->url->title('assi azzar site')->url('http://google.com'))
    ->defaultAction($facebook->buttons->url->url('http://mako.co.il'))
);
Kint::dump($buttons_template->getData());
