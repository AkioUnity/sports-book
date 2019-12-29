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

App::uses('OutcomeProcessorInterface', 'ImportProcessor/Outcomes/Interfaces');

App::uses('Odd1X2Processor', 'ImportProcessor/Odds');


class Outcome1X2Processor implements OutcomeProcessorInterface
{
    protected $OutcomeID = null;

    protected $OutcomeImportID = null;

    protected $EventID = null;

    protected $Name = null;

    protected $Type = null;

    protected $Pick = null;

    protected $Odds = array();

    public function __construct($OutcomeType)
    {
        $this->Type = $OutcomeType;
    }

    public function setOutcomeID($OutcomeID)
    {
        $this->OutcomeID = $OutcomeID;

        return $this;
    }

    public function setOutcomeImportID($OutcomeImportID)
    {
        $this->OutcomeImportID = $OutcomeImportID;

        return $this;
    }

    public function setEventID($EventID)
    {
        $this->EventID = $EventID;

        return $this;
    }

    public function setName($Name)
    {
        $this->Name = $Name;

        return $this;
    }

    public function setPick($Pick)
    {
        $this->Pick = $Pick;

        return $this;
    }

    public function setOdd()
    {
        $OddProcessor = new Odd1X2Processor();

        $this->Odds[] = $OddProcessor;

        return end($this->Odds);
    }

    public function __toArray()
    {
        $Outcome = array(
            'id'        =>  $this->OutcomeID,
            'import_id' =>  $this->OutcomeImportID,
            'event_id'  =>  $this->EventID,
            'name'      =>  $this->Name,
            'type'      =>  $this->Type,
            'pick'      =>  $this->Pick,
            'BetPart'   =>  array()
        );

        foreach ($this->Odds AS $Odd) {
            $Outcome['BetPart'][] = $Odd->__toArray();
        }

        return $Outcome;
    }
}