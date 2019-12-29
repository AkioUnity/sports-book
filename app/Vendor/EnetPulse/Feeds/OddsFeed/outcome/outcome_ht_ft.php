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
class EnetPulse_ObjectParser_outcome_ht_ft extends EnetPulse_ObjectParser_OutcomeTypeProcessor
{
    /**
     * Outcome class name
     *
     * @var string $name
     */
    public $name        =   'Half Time / Full Time';

    /**
     * Mapped & reduced data set
     *
     * @var array $_map
     */
    private $_map       =   array();

    /**
     * Available outcome types
     * Types defined with order
     *
     * @var array $_types
     */
    private $_types     =   array('1/1', '2/1', 'X/1', '1/X', '2/X', '1/2', 'X/2', '2/2', 'X/X');

    /**
     * Outcome (betting type) map function
     * Maps outcome type by options (line, etc)
     *
     * @param array $dataSet - outcome data set
     *
     * @return void
     */
    public function map($dataSet)
    {
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object Betting Type %s Map With Items Count ( %d ) Start.", $this->name, count($dataSet)));

        $hash = function($item) {
            return md5($item["scope"] . $item["dparam"] . $item["objectFK"]);
        };

        foreach($dataSet AS $item) {
            $this->_map[$hash($item)][] = $item;
        }
    }

    /**
     * Outcome (betting tpe) reduce function
     * Ensures valid data from mapped data
     *
     * @return array - returns reduced map
     */
    public function reduce()
    {
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object Betting Type %s Reduce With Items Count ( %d ) Start.", $this->name, count($this->_map)));

        foreach ($this->_map AS $hash => $set) {
            if (count($set) != 9) {
                unset($this->_map[$hash]);
            }
        }
        return $this->_map;
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

    /**
     * Returns insert fields with data
     *
     * @param $betId
     * @param $objectAttributesData
     *
     * @return array
     */
    public function getInsertFields($betId, $objectAttributesData)
    {
        var_dump($objectAttributesData);
        exit;
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object Betting Type %s getInsertFields() With Items Count ( %d ) Start.", $this->name, count($this->_map)));

        return array(
            0   =>  array(
                "import_id" =>  $objectAttributesData[0]["id"],
                "bet_id"    =>  $betId,
                "name"      =>  $objectAttributesData[0]["subtype"],
                "odd"       =>  0,
                "line"      =>  $objectAttributesData[0]["dparam"],
                "order_id"  =>  $this->getOrder($objectAttributesData[0]["subtype"], $this->_types),
            ),
            1   =>  array(
                "import_id" =>  $objectAttributesData[1]["id"],
                "bet_id"    =>  $betId,
                "name"      =>  $objectAttributesData[1]["subtype"],
                "odd"       =>  0,
                "line"      =>  $objectAttributesData[1]["dparam"],
                "order_id"  =>  $this->getOrder($objectAttributesData[1]["subtype"], $this->_types),
            )
        );
    }

    /**
     * Returns update fields with data
     *
     * @param $betId
     * @param $objectAttributesData
     * @param EnetPulse_ObjectParser_outcome_ou $object
     *
     * @return array
     */
    public function getUpdateFields($betId, $objectAttributesData, $object)
    {
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object Betting Type %s getUpdateFields() With Items Count ( %d ) Start.", $this->name, count($this->_map)));

        return array();
    }
}