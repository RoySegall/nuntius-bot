<?php

namespace Nuntius\FacebookSendApi\Templates;

class FacebookTemplates {

  /**
   * @var Button
   */
  public $button;

  /**
   * @var Generic
   */
  public $generic;

  /**
   * @var ListTemplate
   */
  public $list;

  /**
   * @var OpenGraph
   */
  public $openGraph;

  /**
   * @var Receipt
   */
  public $receipt;

  /**
   * @var AirlineBoarding
   */
  public $airlineBoarding;

  /**
   * @var AirlineCheckIn
   */
  public $airlineCheckIn;

  /**
   * @var AirlineItinerary
   */
  public $airlineItinerary;

  /**
   * @var AirlineFlightUpdate
   */
  public $airlineFlightUpdate;

  /**
   * @var Element
   */
  public $element;

  /**
   * @var ReceiptElement
   */
  public $receiptElement;

  /**
   * @var BoardingPass
   */
  public $boardingPass;

  /**
   * @var PassengerInfo
   */
  public $passengerInfo;

  /**
   * @var FlightInfo
   */
  public $flightInfo;

  /**
   * @var PassengerSegmentInfo
   */
  public $passengerSegmentInfo;

  /**
   * @var PriceInfo
   */
  public $priceInfo;

  /**
   * @var UpdateFlightInfo
   */
  public $updateFlightInfo;

  /**
   * FacebookTemplates constructor.
   */
  public function __construct() {
    $this->button = new Button();
    $this->generic = new Generic();
    $this->element = new Element();
    $this->list = new ListTemplate();
    $this->openGraph = new OpenGraph();
    $this->receiptElement = new ReceiptElement();
    $this->receipt = new Receipt();
    $this->airlineBoarding = new AirlineBoarding();
    $this->boardingPass = new BoardingPass();
    $this->airport = new Airport();
    $this->airlineCheckIn = new AirlineCheckIn();
    $this->airlineItinerary = new AirlineItinerary();
    $this->passengerInfo = new PassengerInfo();
    $this->flightInfo = new FlightInfo();
    $this->passengerSegmentInfo = new PassengerSegmentInfo();
    $this->priceInfo = new PriceInfo();
    $this->airlineFlightUpdate = new AirlineFlightUpdate();
    $this->updateFlightInfo = new UpdateFlightInfo();
  }

}
