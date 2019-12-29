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

App::uses('Sport', 'Model');

class EnetPulseXmlObjectQueryFeedParser_sport
{
    public $name = 'Sport';

    /**
     * Sport Model
     *
     * @var Sport $_Sport
     */
    private $_Sport;

    /**
     * Foreign key field map
     *
     * @var array $_FKF
     */
    private $_FKF = array(
        '_tmp'      =>  'sportId',
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

        $this->_Sport                   =   new Sport();

        $this->_schema                  =   array_keys($this->_Sport->schema());
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
        $Sports = $this->_Sport->find("all", array(
            "fields"        =>  array("id", "import_id"),
            "conditions"    =>  array(
                "Sport.import_id" =>  array_map(function($item){ return $item["id"]; }, $this->_map)
            )
        ));

        foreach ($this->_map AS $index => $item) {
            foreach ($Sports AS $Sport) {
                if($item["id"] == $Sport["Sport"]["import_id"]) {
                    $this->_map[$index][$this->_FKF['_tmp']] = $Sport["Sport"]["id"];
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
            $this->_Sport->create();
            if  (!isset($Event[$this->_FKF['_tmp']])) {
                $eventData = array($this->name => $this->getInsertFields($Event));
            } else {
                $eventData = array($this->name => $this->getUpdateFields($Event));
            }

            if (!empty($eventData[$this->name])) {
                $dbData = $this->_Sport->save($eventData);
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
        $insertFields =   array(
            'import_id'     =>  $objectAttributesData["id"],
            'name'          =>  $objectAttributesData["name"],
            'active'        =>  Sport::SPORT_STATE_ACTIVE,
            'order'         =>  $this->_Sport->findLastOrder() + 1,
            'min_bet'       =>  0,
            'max_bet'       =>  0,
            'updated'       =>  strtotime($objectAttributesData["ut"]),
            'feed_type'     =>  'OddService'
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