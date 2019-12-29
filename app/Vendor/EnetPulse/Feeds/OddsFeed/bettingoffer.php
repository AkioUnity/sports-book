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

App::uses('Bet', 'Model');
App::uses('BetPart', 'Model');

class EnetPulse_ObjectParser_bettingoffer
{
    /**
     * Bet Model
     *
     * @var Bet $_Bet
     */
    private $_Bet;

    /**
     * BetPart Model
     *
     * @var BetPart $_BetPart
     */
    private $_BetPart;

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
    private $_objectDataAttributes      = array();

    private $_map                       =   array();

    /**
     * Initializes required classes, data
     *
     * @param array $objectDataAttributes
     */
    public function __construct(array $objectDataAttributes)
    {
        $this->_objectDataAttributes    =   $objectDataAttributes;

        $this->_Bet                     =   new Bet();

        $this->_BetPart                 =   new BetPart();

        $this->_schema                  =   array_keys($this->_BetPart->schema());
    }

    /**
     * Updates, inserts data
     *
     * @param array $objectAttributesData
     *
     * @return void
     */
    public function parseObject($objectAttributesData)
    {
        $this->mapObjects($objectAttributesData);

        $this->reduceObjects();

        $this->saveObjects();

        $this->_map = array();
    }

    public function mapObjects(array $items)
    {
        $BetParts = $this->_BetPart->find("all", array(
            "fields"        =>  array("id", "import_id"),
            "conditions"    =>  array(
                "BetPart.import_id" =>  array_map(function($item){ return $item["outcomeFK"]; }, $items)
            )
        ));

        foreach ($BetParts AS $BetPart) {
            foreach ($items AS $item) {
                if($item["outcomeFK"] == $BetPart["BetPart"]["import_id"]) {
                    $item["BetPartId"] = $BetPart["BetPart"]["id"];
                    $this->_map[] = $item;
                }
            }
        }
    }

    public function reduceObjects()
    {
        foreach($this->_map AS $index => $map) {
            $this->_map[$index] = array(
                "id"    =>  $map["BetPartId"],
                "odd"   =>  $map["odds"]
            );
        }
    }

    public function saveObjects()
    {
        $this->_BetPart->saveAll($this->_map);
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
        $insertFields = array();

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
}