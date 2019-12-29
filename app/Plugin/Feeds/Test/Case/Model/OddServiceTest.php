<?php

App::uses('FeedsAppModel', 'Feeds.Model');
App::uses('Ticket', 'Model');

class OddServiceTest extends CakeTestCase
{
    public $fixtures = array('plugin.Feeds.OddService');

    /**
     * OddService Model
     *
     * @var OddService
     */
    private $OddService;

    public function setUp() {
        parent::setUp();
        $this->OddService = ClassRegistry::init('Feeds.OddService');
    }

    /**
     * get OddStatus
     *
     * @test case
     */
    public function getOddStatus() {
        $this->assertEqual(Ticket::TICKET_STATUS_WON, $this->OddService->getOddStatus(OddService::ODD_TYPE_WON));
        $this->assertEqual(Ticket::TICKET_STATUS_LOST, $this->OddService->getOddStatus(OddService::ODD_TYPE_LOST));
        $this->assertEqual(Ticket::TICKET_STATUS_CANCELLED, $this->OddService->getOddStatus(OddService::ODD_TYPE_CANCELLED));
        $this->assertEqual(Ticket::TICKET_STATUS_PENDING, $this->OddService->getOddStatus(9999999999E9));
        $this->assertEqual(Ticket::TICKET_STATUS_PENDING, $this->OddService->getOddStatus('NOT_EXISTING_ODD'));
    }

    /**
     * Get Imported Bet From Imported Event Data
     *
     * @test case
     */
    public function getImportedBet()
    {
        $this->assertEqual(is_array($this->OddService->getImportedBet(array(), null)), true);
        $this->assertEqual(is_array($this->OddService->getImportedBet(array('Bet' => array()), null)), true);
        $this->assertEqual(is_array($this->OddService->getImportedBet(array('Bet' => array(0 => array('import_id' => 5))), null)), true);
        $this->assertEqual(is_array($this->OddService->getImportedBet(array('Bet' => array(0 => array('Bet' => array('import_id' => 5)))), null)), true);
        $this->assertEqual(is_array($this->OddService->getImportedBet(array('Bet' => array(0 => array('Bet' => array('import_id' => 5)))), 5)), true);
    }

    /**
     * Check has event any valid outcome
     *
     * @test case
     */
    public function hasValidOutcomes()
    {
        $this->assertEqual( $this->OddService->hasValidOutcomes(new SimpleXMLElement('<xml></xml>')) == true, false );
        $this->assertEqual( $this->OddService->hasValidOutcomes(new SimpleXMLElement('<Outcomes><Outcome id="2" name="TODO:FAIL"></Outcome></Outcomes>') ) == true, false );
        $this->assertEqual( $this->OddService->hasValidOutcomes(new SimpleXMLElement('<Outcomes><Outcome id="2" name="Under/Over"></Outcome></Outcomes>') ) == true, true );
    }

    /**
     *
     * @test case
     */
    public function hasOutcomeValidOdds()
    {
        $this->assertEqual( $this->OddService->hasOutcomeValidOdds(new SimpleXMLElement('<xml></xml>')), false );
        $this->assertEqual( $this->OddService->hasOutcomeValidOdds(new SimpleXMLElement('<xml><Outcome></Outcome></xml>')), false );
        $this->assertEqual( $this->OddService->hasOutcomeValidOdds(new SimpleXMLElement('<Outcomes><Outcome id="2" name="Under/Over"><Bookmaker id="75" name="OddService"><Odds bet="TODO:fail" /></Bookmaker></Outcome></Outcomes>')), false );
        $this->assertEqual( $this->OddService->hasOutcomeValidOdds(new SimpleXMLElement('<Outcomes><Outcome id="2" name="Under/Over"><Bookmaker id="75" name="OddService"><Odds Status="TODO:FAIL" /></Bookmaker></Outcome></Outcomes>')), false );
        $this->assertEqual( $this->OddService->hasOutcomeValidOdds(new SimpleXMLElement('<Outcomes><Outcome id="2" name="Under/Over"><Bookmaker id="75" name="OddService"><Odds Status="Open" /></Bookmaker></Outcome></Outcomes>')), true );
    }

    /**
     *
     * @test case
     */
    public function getOutcomeValidOdds()
    {
        $Odds = '<Outcomes>
        <Outcome id="2" name="Under/Over">
          <Bookmaker id="75" name="OddService" isLive="false" lastUpdate="2013-12-09T22:06:18.837" bookieEventID="" bookieLeagueID="">
            <Odds bet="Over" startPrice="1.01" currentPrice="1.03" line="0.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
            <Odds bet="Under" startPrice="1.01" currentPrice="1.01" line="0.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
            <Odds bet="Over" startPrice="1.01" currentPrice="1.05" line="1.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
            <Odds bet="Under" startPrice="1.01" currentPrice="1.05" line="1.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
            <Odds bet="Over" startPrice="1.01" currentPrice="1.01" line="2.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
            <Odds bet="Under" startPrice="1.02" currentPrice="1.02" line="2.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
            <Odds bet="Over" startPrice="1.01" currentPrice="1.01" line="3.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
            <Odds bet="Under" startPrice="1.02" currentPrice="1.02" line="3.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
            <Odds bet="Over" startPrice="1.5" currentPrice="1.5" line="4.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" isBase="true" />
            <Odds bet="Under" startPrice="1.05" currentPrice="1.05" line="4.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" isBase="true" />
            <Odds bet="Over" startPrice="1.02" currentPrice="1.02" line="5.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
            <Odds bet="Under" startPrice="1.02" currentPrice="1.02" line="5.5" LastUpdate="2013-12-09T22:06:18.837" bookieOutcomeID="" Status="Open" />
          </Bookmaker>
        </Outcome>
      </Outcomes>';
        //$this->OddService->getOutcomeValidOdds('sdsd', new SimpleXMLElement($Odds)  );
    }
}