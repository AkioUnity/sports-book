<?php
App::uses('Controller', 'Controller');
App::uses('Model', 'Model');
App::uses('View', 'View');

App::import('Xml');
App::uses('CakeEvent', 'Event');
App::uses('CakeEventManager', 'Event');
App::uses('OddsServiceListener', 'Feeds.Event');

App::uses('ImportProcessor', 'ImportProcessor');
App::uses('UpdateProcessor', 'UpdateProcessor');

class OddServiceControllerTest extends ControllerTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        0   =>  'app.Country',
        1   =>  'app.League',
        2   =>  'app.Sport',
        3   =>  'app.Event',
        4   =>  'app.Bet',
        5   =>  'app.BetPart'
    );

    /** @var OddServiceController  $_OddServiceController */
    private $_OddServiceController;

    private $xml;

    /**
     * Feed Provider Name
     */
    const FEED_PROVIDER = 'OddService';

    /**
     * Setup the test case, backup the static object values so they can be restored.
     */
    public function setUp() {
        $this->_OddServiceController = $this->generate('Feeds.OddService',
            array(
                'components' => array(
                    'Security' => array( '_validatePost', '_validateCsrf')
                )
            )
        );
        parent::setUp();
    }

    public function testImportEvents() {

        /** @var SimpleXMLElement $xml */
        $this->xml = $this->_OddServiceController->OddService->download('OddService', 'Events', $this->_OddServiceController->eventsXml, false, true);

        /** Is Object? */
        $this->assertEqual(is_object($this->xml), true);

        /** Children: Header, Result */
        $this->assertEqual($this->xml->count(), 2);

        /** Is object header? */
        $this->assertEqual(is_object($this->xml->Header), true);

        $this->assertEqual(is_object($this->xml->Results), true);

        foreach ($this->xml->Results->children() AS $Event) {
            /** Do we have EventID */
            $this->assertEqual(is_object($Event->EventID), true);

            /** Do we have StartDate */
            $this->assertEqual(is_object($Event->StartDate), true);

            /** Do we have SportID  */
            $this->assertEqual(is_object($Event->SportID), true);

            /** Do we have LeagueID */
            $this->assertEqual(is_object($Event->LeagueID), true);

            /** Do we have LocationID */
            $this->assertEqual(is_object($Event->LocationID), true);

            /** Do we have Status */
            $this->assertEqual(is_object($Event->Status), true);

            /** Do we have LastUpdate */
            $this->assertEqual(is_object($Event->LastUpdate), true);

            /** Do we have CoverageLevel */
            $this->assertEqual(is_object($Event->CoverageLevel), true);

            /** Do we have HomeTeam */
            $this->assertEqual(is_object($Event->HomeTeam), true);

            /** Do we have AwayTeam */
            $this->assertEqual(is_object($Event->AwayTeam), true);

            /** Do we have outcomes */
            $this->assertEqual(is_object($Event->Outcomes), true);

            $ImportEventProcessor = ImportProcessor::setEvent();

            $ImportEventProcessor->setEventImportID( $Event->EventID->__toString() );

            $ImportEventProcessor->setEventName( $Event->HomeTeam->attributes()->Name->__toString() . ' - ' . $Event->AwayTeam->attributes()->Name->__toString() );

            $ImportEventProcessor->setStartDate( $Event->StartDate->__toString() );

            $ImportEventProcessor->setLeagueImportID( $Event->LeagueID->__toString() );

            $ImportEventProcessor->setFeedProvider( self::FEED_PROVIDER );

            foreach ($Event->Outcomes->Outcome AS $Outcome) {

                $OutcomeProcessor =     $ImportEventProcessor
                                  ->    setOutcome( $Outcome->attributes()->name->__toString() )
                                  ->    setOutcomeImportID( $Outcome->attributes()->id->__toString() )
                                  ->    setName( $Outcome->attributes()->name->__toString() );

                foreach ($Outcome->Bookmaker->xpath('Odds') AS $Odd) {

                                        $OutcomeProcessor->setOdd()
                                  ->    setOddImportID( $Odd->attributes()->id->__toString() )
                                  ->    setName( $Odd->attributes()->bet->__toString() )
                                  ->    setOdd( (float) $Odd->attributes()->currentPrice->__toString() )
                                  ->    setLine( $Odd->attributes()->line->__toString() )
                                  ->    setOrderID();
                }
            }

            $ImportedEvent      = $this->_OddServiceController->Event->getEventByImportId($ImportEventProcessor->getImportID(), 'OddService');
            $ImportEventArray   = $ImportEventProcessor->__toArray();

            /**
             * TODO:
             *
             * lyginti pagal importID
             *
             * 1. EventID.
             * 2. BetID
             * 3. BetPartID
             *
             */

            // Validuoti pačius tipus odds'ų
            // Bandyti update'inti, kurti naują

            $Validator = ImportProcessor::setValidator($ImportEventArray, $ImportedEvent);
// 1533462

var_dump($Validator);
            exit;

            exit;
        }
    }
}