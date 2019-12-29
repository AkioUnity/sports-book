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

App::uses('Country', 'Model');
App::uses('League', 'Model');

class EnetPulseXmlObjectQueryFeedParser_tournament_stage
{
    public $name = 'League';

    /**
     * Foreign key field map
     *
     * @var array $_FKF
     */
    private $_FKF = array(
        '_tmp'      =>  'leagueId',
        '_schema'   =>  'id'
    );

    /**
     * League Model
     *
     * @var League $_League
     */
    private $_League;

    /**
     * Country Model
     *
     * @var Country $_Country
     */
    private $_Country;

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

        $this->_League                  =   new League();

        $this->_Country                 =   new Country();

        $this->_schema                  =   array_keys($this->_League->schema());
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

    /**
     * @return void
     */
    public function reduceObjects()
    {
        $Leagues = $this->_League->find("all", array(
            "fields"        =>  array("id", "import_id"),
            "conditions"    =>  array(
                "League.import_id" =>  array_map(function($item){ return $item["id"]; }, $this->_map)
            )
        ));

        foreach ($this->_map AS $index => $item) {
            foreach ($Leagues AS $League) {
                if($item["id"] == $League["League"]["import_id"]) {
                    $this->_map[$index][$this->_FKF['_tmp']] = $League["League"]["id"];
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
        foreach ($this->_map AS $index => $League) {
            $this->_League->create();
            if  (!isset($League[$this->_FKF['_tmp']])) {
                $leagueData = array($this->name => $this->getInsertFields($League));
            } else {
                $leagueData = array($this->name => $this->getUpdateFields($League));
            }

            if (!empty($leagueData[$this->name])) {
                $dbData = $this->_League->save($leagueData);
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
        $sport                  =   array();
        $tournament             =   array();
        $tournament_template    =   array();

        $CountryData    =   $this->_Country->find('first', array(
            'recursive'     =>  -1,
            'fields'        =>  array('Country.id'),
            'contain'       =>  array(),
            'conditions'    =>  array(
                'Country.import_id'  => $objectAttributesData["countryFK"]
            )
        ));

        if(!isset($CountryData["Country"]["id"])) {
            throw new Exception('', 500);
        }

        foreach (EnetPulseXmlObjectQueryFeed::loadObjectClass('tournament')->getMapObjects() AS $tournamentMap) {
            if($objectAttributesData["tournamentFK"] == $tournamentMap["id"]) {
                $tournament = $tournamentMap;
                break;
            }
        }

        if (empty($tournament)) {
            throw new Exception(sprintf("Tournament object empty"), 500);
        }

        foreach(EnetPulseXmlObjectQueryFeed::loadObjectClass('tournament_template')->getMapObjects() AS $tournament_templateMap) {
            if($tournament["tournament_templateFK"] == $tournament_templateMap["id"]) {
                $tournament_template = $tournament_templateMap;
                break;
            }
        }

        if (empty($tournament_template)) {
            throw new Exception(sprintf("Tournament_Template object empty"), 500);
        }

        foreach (EnetPulseXmlObjectQueryFeed::loadObjectClass('sport')->getMapObjects() AS $sportMap) {
            if ($sportMap["id"] == $tournament_template["sportFK"]) {
                $sport = $sportMap;
                break;
            }
        }

        $FKF = EnetPulseXmlObjectQueryFeed::loadObjectClass('sport')->getFKF();

        if (empty($sport) || !isset($sport[$FKF['_tmp']])) {
            var_dump($sport);
            throw new Exception(sprintf("Sport object or FKF empty"), 500);
        }

        $insertFields   =   array(
            "import_id"     =>  $objectAttributesData["id"],
            "country_id"    =>  $CountryData["Country"]["id"],
            "name"          =>  $objectAttributesData["name"],
            "active"        =>  League::LEAGUE_STATE_ACTIVE,
            "order"         =>  $this->_League->findLastOrder() + 1,
            "sport_id"      =>  $sport[$FKF['_tmp']],
            "updated"       =>  strtotime($objectAttributesData["ut"]),
            "feedType"      =>  'OddService'
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
}