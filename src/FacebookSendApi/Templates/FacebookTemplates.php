<?php

namespace Nuntius\FacebookSendApi\Templates;

class FacebookTemplates {

  public $button;

  public $generic;

  public $list;

  public $openGraph;

  public $receipt;

  public $airlineBoarding;

  public $airlineCheckIn;

  public $airlineItinerary;

  public $airlineFlightUpdate;

  public $element;

  public function __construct() {
    $this->button = new Button();
    $this->generic = new Generic();
    $this->element = new Element();
    $this->list = new ListTemplate();
//    $this->openGraph = new OpenGraph();
//    $this->receipt = new Receipt();
//    $this->airlineBoarding = new AirlineBoarding();
//    $this->airlineCheckIn = new AirlineCheckIn();
//    $this->airlineItinerary = new AirlineItinerary();
//    $this->airlineFlightUpdate = new AirlineFlightUpdate();
  }

}
