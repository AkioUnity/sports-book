<?php
 /**
 * Short description for class
 *
 * Long description for class EnetPulse_ObjectParser_(if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('Result', 'Events.Model');

class EnetPulseXmlObjectQueryFeedParser_result
{
    public $name = "Result";

    /**
     * Result Model
     *
     * @var Result $_Result
     */
    private $_Result;

    /**
     * event_participants Model
     *
     * @var EnetPulseXmlObjectQueryFeedParser_event $_event
     */
    private $_event;

    /**
     * event_participants Model
     *
     * @var EnetPulseXmlObjectQueryFeedParser_event_participants $_event_participants
     */
    private $_event_participants;

    /**
     * Model schema
     *
     * @var array $_schema
     */
    private $_schema = array();

    /**
     * Object data attributes
     *
     * @var array $_objectDataAttributes
     */
    private $_objectDataAttributes      =   array();

    private $_map                       =   array();

    /**
     * Foreign key field map
     *
     * @var array $_FKF
     */
    private $_FKF = array(
        '_tmp'      =>  'resultId',
        '_schema'   =>  'id'
    );

    public $_failMapFK                  =   'failed_map_foreign_key';

    private $_resultCodeMap             =   array(
        0   =>  array('resultsValue' => 'ordinarytime', 'betsValue' => 'ord', 'active' => true),
        1   =>  array('resultsValue' => 'runningscore', 'betsValue' => '', 'active' => false),
        2   =>  array('resultsValue' => 'halftime', 'betsValue' => '', 'active' => false),
        3   =>  array('resultsValue' => 'finalresult', 'betsValue' => '', 'active' => false),
        4   =>  array('resultsValue' => 'setswon', 'betsValue' => '', 'active' => false),
        5   =>  array('resultsValue' => 'gamescore', 'betsValue' => '', 'active' => false),
        6   =>  array('resultsValue' => 'tiebreak2', 'betsValue' => '', 'active' => false),
        7   =>  array('resultsValue' => 'extratime', 'betsValue' => '', 'active' => false),
        8   =>  array('resultsValue' => 'quarter1', 'betsValue' => '1q', 'active' => true),
        9   =>  array('resultsValue' => 'quarter2', 'betsValue' => '2q', 'active' => true),
        10  =>  array('resultsValue' => 'quarter3', 'betsValue' => '3q', 'active' => true),
        11  =>  array('resultsValue' => 'quarter4', 'betsValue' => '4q', 'active' => true),
        12  =>  array('resultsValue' => 'period1', 'betsValue' => '1p', 'active' => true),
        13  =>  array('resultsValue' => 'period2', 'betsValue' => '2p', 'active' => true),
        14  =>  array('resultsValue' => 'period3', 'betsValue' => '3p', 'active' => true),
        15  =>  array('resultsValue' => 'set1', 'betsValue' => '1s', 'active' => true),
        16  =>  array('resultsValue' => 'set2', 'betsValue' => '2s', 'active' => true),
        17  =>  array('resultsValue' => 'set3', 'betsValue' => '3s', 'active' => true),
        18  =>  array('resultsValue' => 'set4', 'betsValue' => '4s', 'active' => true),
        19  =>  array('resultsValue' => 'set5', 'betsValue' => '5s', 'active' => true),
    );

    /**
     * Initializes required classes, data
     *
     * @param array $objectDataAttributes
     */
    public function __construct(array $objectDataAttributes)
    {
        $this->_objectDataAttributes    =   $objectDataAttributes;
        $this->_Result                  =   new Result();
        $this->_event                   =   EnetPulseXmlObjectQueryFeed::loadObjectClass('event');
        $this->_event_participants      =   EnetPulseXmlObjectQueryFeed::loadObjectClass('event_participants');
        $this->_schema                  =   array_keys($this->_Result->schema());
    }

    public function initObject($objectAttributesData)
    {
        $this->mapObjects($objectAttributesData);
    }

    /**
     * Updates, inserts data
     *
     * @return void
     */
    public function processObject()
    {
        $this->reduceObjects();

        $this->saveObjects();
    }

    /**
     *
     * @param array $items
     *
     * @return void
     *
     */
    public function mapObjects(array $items)
    {
        $event              = array();
        $event_participants = array();

        /**
         * TODO:
         *
         * kodėl tame pačiame xml'e neturiu prareduce'into?
         * Tame pačiame xml'e turėčiau turėti visus related object'us :|
         */
        $this->_event->reduceObjects();
        $this->_event_participants->reduceObjects();

        $event_FKF              =   $this->_event->getFKF();;
        $event_participantFKF   =   $this->_event_participants->getFKF();
        $eventId                =   $this->_failMapFK;

        foreach ($this->_event_participants->getMapObjects() AS $objectsSet) {
            foreach($objectsSet AS $object) {
                $participantId = $this->_failMapFK;
                if (isset($object[$event_participantFKF["_tmp"]])) {
                    $participantId = $object[$event_participantFKF["_tmp"]];
                }

                foreach ($this->_event->getMapObjects() AS  $eventObject) {
                    if (isset($eventObject[$event_FKF["_tmp"]]) && $object["eventFK"] == $eventObject["id"]) {
                        $eventId = $eventObject[$event_FKF["_tmp"]];
                    }
                }
                $event_participants[$object["id"]] = array(
                    "event_id"                      =>  $object["eventFK"],
                    $event_FKF["_tmp"]              =>  $eventId,
                    $event_participantFKF["_tmp"]   =>  $participantId
                );
            }
        }

        $thisReference = $this;

        $hash = function($item) USE ($event_participants, $event_FKF, $event_participantFKF, $thisReference) {

            if (!isset($event_participants[$item["event_participantsFK"]])) {
                return $thisReference->_failMapFK;
            }

            if ($event_participants[$item["event_participantsFK"]][$event_participantFKF["_tmp"]] == $thisReference->_failMapFK) {
                return $thisReference->_failMapFK;
            }

            if ($event_participants[$item["event_participantsFK"]][$event_FKF["_tmp"]] == $thisReference->_failMapFK) {
                return $thisReference->_failMapFK;
            }

            return md5($event_participants[$item["event_participantsFK"]]["event_id"] . $item["result_code"]);
        };

        foreach ($items AS $item) {

            $item[$event_FKF["_tmp"]] = $this->_failMapFK;
            $item[$event_participantFKF["_tmp"]] = $this->_failMapFK;

            if (isset($event_participants[$item["event_participantsFK"]][$event_participantFKF["_tmp"]])) {
                $item[$event_participantFKF["_tmp"]] = $event_participants[$item["event_participantsFK"]][$event_participantFKF["_tmp"]];
            }

            if (isset($event_participants[$item["event_participantsFK"]][$event_FKF["_tmp"]])) {
                $item[$event_FKF["_tmp"]] = $event_participants[$item["event_participantsFK"]][$event_FKF["_tmp"]];
            }

            $this->_map[$hash($item)][] = $item;
        }
    }

    public function getMapObjects()
    {
        return $this->_map;
    }

    /**
     * @return void
     */
    public function reduceObjects()
    {
        $map_ids                =   array();
        $event_FKF              =   $this->_event->getFKF();
        $event_participantFKF   =   $this->_event_participants->getFKF();

        if (isset($this->_map[$this->_failMapFK])) {
            unset($this->_map[$this->_failMapFK]);
        }

        foreach ($this->_map AS $index => $objectSet) {
            if (count($objectSet) != 2) {
                unset($this->_map[$index]);
            } else {
                foreach ($objectSet AS $resultObject) {
                    if(!isset($resultObject[$event_FKF["_tmp"]]) || !isset($resultObject[$event_participantFKF["_tmp"]])) {
                        unset($this->_map[$index]);
                    } else {
                        if($resultObject[$event_FKF["_tmp"]] == $this->_failMapFK || $resultObject[$event_participantFKF["_tmp"]] == $this->_failMapFK) {
                            unset($this->_map[$index]);
                        } else {
                            $map_ids[] = $resultObject["id"];
                        }
                    }
                }
            }
        }

        if (!empty($map_ids)) {
            $Results = $this->_Result->find("all", array(
                "fields"        =>  array("id", "import_id"),
                "conditions"    =>  array(
                    "Result.import_id" =>  $map_ids
                )
            ));

            foreach ($this->_map AS $hash => $set) {
                foreach ($Results AS $Result) {
                    foreach ($set AS $index => $item) {
                        if($item["id"] == $Result["Result"]["import_id"]) {
                            $this->_map[$hash][$index][$this->_FKF['_tmp']] = $Result["Result"]["id"];
                        }
                    }
                }
            }
        }
    }

    /**
     * Saves mapped and reduced data
     *
     * @return array
     */
    public function saveObjects()
    {
        foreach ($this->_map AS $index => $resultsSet) {
            foreach ($resultsSet AS $Result) {
                $this->_Result->create();
                if  (!isset($Result[$this->_FKF['_tmp']])) {
                    $resultData = array($this->name => $this->getInsertFields($Result));
                } else {
                    $resultData = array($this->name => $this->getUpdateFields($Result));
                }

                if (!empty($resultData[$this->name])) {
                    $dbData = $this->_Result->save($resultData);
                    $this->_map[$index][$this->_FKF['_tmp']] = $dbData[$this->name]["id"];
                }
            }
        }
    }

    /**
     * Returns insert fields with data
     *
     * @param array $objectAttributesData
     *
     * @return array
     */
    public function getInsertFields($objectAttributesData)
    {
        $event_FKF              =   $this->_event->getFKF();
        $event_participantFKF   =   $this->_event_participants->getFKF();

        $insertFields           =   array(
            "import_id"         =>  $objectAttributesData["id"],
            "participant_id"    =>  $objectAttributesData[$event_participantFKF["_tmp"]],
            "event_id"          =>  $objectAttributesData[$event_FKF["_tmp"]],
            "result_code"       =>  $this->getResultCode($objectAttributesData["result_code"]),
            "value"             =>  (int) $objectAttributesData["value"]
        );

        return $insertFields;
    }

    /**
     * Returns update fields with data
     *
     * @param array $objectAttributesData
     *
     * @return array
     */
    public function getUpdateFields($objectAttributesData)
    {
        $updateFields =   array();

        foreach ($this->_objectDataAttributes AS $attribute) {

        }

        return $updateFields;
    }

    /**
     * Flush map & reduce results
     *
     * @return void
     */
    public function reset()
    {
        $this->_map = array();
    }

    private function getResultCode()
    {

    }
}