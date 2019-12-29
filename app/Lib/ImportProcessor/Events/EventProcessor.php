<?php
 /**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('OutcomeProcessor', 'ImportProcessor/Outcomes');

class EventProcessor {

    protected $EventID = null;

    protected $EventImportID = null;

    protected $EventName = null;

    protected $StartDate = null;

    protected $Result = null;

    protected $Active = null;

    protected $LeagueID = null;

    protected $LeagueImportID = null;

    protected $FeedProvider = null;

    protected $Outcomes = array();

    public function setEventImportID($EventImportID)
    {
        $this->EventImportID = $EventImportID;

        return $this;
    }

    public function getImportID()
    {
        return $this->EventImportID;
    }

    public function setEventName($EventName)
    {
        $this->EventName = $EventName;

        return $this;
    }

    public function setStartDate($StartDate)
    {
        $this->StartDate = $StartDate;

        return $this;
    }

    public function setResult($Result)
    {
        $this->Result = $Result;

        return $this;
    }

    public function setActive($Active)
    {
        $this->Active = $Active;

        return $this;
    }

    public function setLeagueID($LeagueID)
    {
        $this->LeagueID = $LeagueID;

        return $this;
    }

    public function setLeagueImportID($LeagueImportID)
    {
        $this->LeagueImportID = $LeagueImportID;

        return $this;
    }

    public function setFeedProvider($FeedProvider)
    {
        $this->FeedProvider = $FeedProvider;

        return $this;
    }

    /**
     * @param $OutcomeType
     *
     * @return OutcomeProcessor
     */
    public function setOutcome($OutcomeType)
    {
        $OutcomeProcessor = new OutcomeProcessor($OutcomeType);

        $OutcomeTypeProcessor = $OutcomeProcessor->getOutcomeTypeProcessor();

        $this->Outcomes[] = $OutcomeTypeProcessor;

        return end($this->Outcomes);
    }

    public function getOutcome($OutcomeIndex)
    {
        return $this->Outcomes[$OutcomeIndex];
    }

    public function __toArray()
    {
        $Event = array(
            'Event' =>  array(
                'id'        =>  $this->EventID,
                'import_id' =>  $this->EventImportID,
                'name'      =>  $this->EventName,
                'date'      =>  $this->StartDate,
                'result'    =>  $this->Result,
                'active'    =>  $this->Active,
                'league_id' =>  $this->LeagueID,
                'feed_type' =>  $this->FeedProvider
            )
        );

        foreach ($this->Outcomes AS $Outcome) {
            $Event['Bet'][] = $Outcome->__toArray();
        }

        return $Event;
    }
}