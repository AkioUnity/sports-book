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

App::uses('Event', 'Model');
App::uses('League', 'Model');

class OddServiceXmlInPlayParser_Event
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

    private $_map                       =   array();

    /**
     * Initializes required classes, data
     */
    public function __construct()
    {
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

    public function initObject(array $objectAttributesData)
    {
        $this->mapObjects($objectAttributesData);
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

    public function processObject()
    {
        $this->reduceObjects();

        $this->saveObjects();
    }
    /**
     * @return void
     */
    public function reduceObjects()
    {
        $Events = $this->_Event->find("all", array(
            "contain"       =>  array(),
            "fields"        =>  array("id", "import_id"),
            "conditions"    =>  array(
                "Event.import_id"   =>  array_map(function($item){ return $item["Event"]["EventID"]; }, $this->_map),
                "Event.type"        =>  Event::EVENT_TYPE_LIVE
            )
        ));

        foreach ($this->_map AS $index => $item) {
            foreach ($Events AS $Event) {
                if($item["Event"]["EventID"] == $Event["Event"]["import_id"]) {
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
        $Event = $this->_Event->find("first", array(
            "contain"       =>  array(),
            "conditions"    =>  array(
                "Event.import_id"   =>  $objectAttributesData["Event"]["EventID"],
                "Event.type"        =>  Event::EVENT_TYPE_PREMATCH
            )
        ));

        if (!empty($Event["Event"])) {
            unset($Event["Event"]["id"]);
            $Event["Event"]["type"] = Event::EVENT_TYPE_LIVE;
            $Event["Event"]["result"] = "";
            return $Event["Event"];
        }

        return array();
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