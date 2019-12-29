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

App::uses('Event', 'Model');
App::uses('Country', 'Model');
App::uses('Participant', 'Events.Model');

class EnetPulseXmlObjectQueryFeedParser_event_participants
{
    public $name = 'Participant';

    /**
     * Participant Model
     *
     * @var Participant $_Participant
     */
    private $_Participant;

    /**
     * Country Model
     *
     * @var Country $_Country
     */
    private $_Country;

    /**
     * Event Model
     *
     * @var Event $_Event
     */
    private $_Event;

    /**
     * Foreign key field map
     *
     * @var array $_FKF
     */
    private $_FKF = array(
        '_tmp'      =>  'participantId',
        '_schema'   =>  'id'
    );

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
     * Initializes required classes, data
     *
     * @param array $objectDataAttributes
     */
    public function __construct(array $objectDataAttributes)
    {
        $this->_objectDataAttributes    =   $objectDataAttributes;

        $this->_Participant             =   new Participant();

        $this->_Country                 =   new Country();

        $this->_Event                   =   new Event();

        $this->_schema                  =   array_keys($this->_Participant->schema());
    }

    /**
     *
     * @return array
     */
    public function getFKF()
    {
        return $this->_FKF;
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
        $hash = function($item) {
            return md5($item["eventFK"]);
        };

        foreach($items AS $item) {
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
        $event_ids          =   array();
        $participant_ids    =   array();

        foreach ($this->_map AS $hash => $set) {
            if (count($set) != 2) {
                unset($this->_map[$hash]);
            } else {
                $event_ids          =   array_map(function($item){ return $item["eventFK"]; }, $set);
                $participant_ids    =   array_map(function($item){ return $item["participantFK"]; }, $set);
            }
        }

        if (!empty($event_ids) || !empty($participant_ids)) {

            $Event = $this->_Event->find("first", array(
                "contain"       =>  array(),
                "fields"        =>  array("id", "import_id"),
                "conditions"    =>  array(
                    "Event.import_id" =>  $event_ids
                )
            ));

            if (!empty($Event)) {
                $Participants = $this->_Participant->find("all", array(
                    "contain"       =>  array(),
                    "fields"        =>  array("id", "import_id"),
                    "conditions"    =>  array(
                        "Participant.import_id" =>  $participant_ids,
                        "Participant.event_id"  =>  $Event["Event"]["id"]
                    )
                ));

                foreach ($this->_map AS $hash => $set) {
                    foreach ($Participants AS $Participant) {
                        foreach ($set AS $index => $item) {
                            if($item["participantFK"] == $Participant["Participant"]["import_id"]) {
                                $this->_map[$hash][$index][$this->_FKF['_tmp']] = $Participant["Participant"]["id"];
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Saves mapped and reduced data
     *
     * @return void
     */
    public function saveObjects()
    {
        foreach ($this->_map AS $hash => $set) {
            foreach ($set AS $index => $item) {
                $this->_Participant->create();
                if  (!isset($item[$this->_FKF['_tmp']])) {
                    $participantData = array($this->name => $this->getInsertFields($item));
                } else {
                    $participantData = array($this->name => $this->getUpdateFields($item));
                }

                if (!empty($participantData[$this->name])) {
                    $dbData = $this->_Participant->save($participantData);
                    $this->_map[$hash][$index][$this->_FKF['_tmp']] = $dbData[$this->name]["id"];
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
        $participant    =   array();
        $event          =   array();
        $eventFKF       =   EnetPulseXmlObjectQueryFeed::loadObjectClass('event')->getFKF();

        foreach (EnetPulseXmlObjectQueryFeed::loadObjectClass('participant')->getMapObjects() AS $participantsMap) {
            if($objectAttributesData["participantFK"] == $participantsMap["id"]) {
                $participant = $participantsMap;
            }
        }

        foreach (EnetPulseXmlObjectQueryFeed::loadObjectClass('event')->getMapObjects() AS $eventsMap) {
            if($objectAttributesData["eventFK"] == $eventsMap["id"]) {
                $event = $eventsMap;
            }
        }

        $country        =   $this->_Country->find("first", array(
            "contain"       =>  array(),
            "fields"        =>  array("Country.id"),
            "conditions"    =>  array(
                "Country.import_id"  => $participant["countryFK"]
            )
        ));

        if (empty($participant) || empty($event) || empty($country)) {
            return array();
        }

        return array(
            "import_id"     =>  $objectAttributesData["participantFK"],
            "event_id"      =>  $event[$eventFKF['_tmp']],
            "country_id"    =>  $country["Country"]["id"],
            "number"        =>  $objectAttributesData["number"],
            "name"          =>  $participant["name"],
            "type"          =>  $participant["type"],
            "gender"        =>  $participant["gender"],
        );
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
}