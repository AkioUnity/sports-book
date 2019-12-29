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
App::uses('League', 'Model');

class EnetPulseXmlObjectQueryFeedParser_event
{
    public $name = 'Event';
    /**
     * Foreign key field map
     *
     * @var array $_FKF
     */
    private $_FKF = array(
        '_tmp'      =>  'eventId',
        '_schema'   =>  'id'
    );

    /**
     * Model schema
     *
     * @var array $_schema
     */
    private $_schema = array();

    /**
     * Event Model
     *
     * @var Event $_Model
     */
    private $_Event;

    /**
     * League Model
     *
     * @var League $_League
     */
    private $_League;

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

        $this->_Event                   =   new Event();

        $this->_League                  =   new League();

        $this->_schema                  =   array_keys($this->_Event->schema());
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
        $this->_map = $items;
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
        $Events = $this->_Event->find("all", array(
            "fields"        =>  array("id", "import_id"),
            "conditions"    =>  array(
                "Event.import_id" =>  array_map(function($item){ return $item["id"]; }, $this->_map)
            )
        ));

        foreach ($this->_map AS $index => $item) {
            foreach ($Events AS $Event) {
                if($item["id"] == $Event["Event"]["import_id"]) {
                    $this->_map[$index][$this->_FKF['_tmp']] = $Event["Event"]["id"];
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
        foreach ($this->_map AS $index => $Event) {
            $this->_Event->create();
            if  (!isset($Event[$this->_FKF['_tmp']])) {
                $eventData = array($this->name => $this->getInsertFields($Event));
            } else {
                $eventData = array($this->name => $this->getUpdateFields($Event));
            }

            if (!empty($eventData[$this->name])) {
                $dbData = $this->_Event->save($eventData);
                $this->_map[$index][$this->_FKF['_tmp']] = $dbData[$this->name]["id"];
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
        $LeagueData     =   $this->_League->find('first', array(
            'recursive'     =>  -1,
            'fields'        =>  array('League.id'),
            'contain'       =>  array(),
            'conditions'    =>  array(
                'League.import_id'  => $objectAttributesData["tournament_stageFK"]
            )
        ));

        $LeagueId       =   !empty($LeagueData["League"]["id"]) ? $LeagueData["League"]["id"] : 0;

        return array(
            "import_id" =>  $objectAttributesData["id"],
            "name"      =>  $objectAttributesData["name"],
            "date"      =>  $objectAttributesData["startdate"],
            "result"    =>  "",
            "active"    =>  Event::EVENT_ACTIVE_STATE,
            "league_id" =>  $LeagueId,
            "feed_type" =>  "OddService"
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
        return array();
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