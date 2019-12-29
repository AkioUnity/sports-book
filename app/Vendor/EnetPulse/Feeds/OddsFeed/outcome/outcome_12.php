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

class EnetPulse_ObjectParser_outcome_12 extends EnetPulse_ObjectParser_OutcomeTypeProcessor
{
    /**
     * Outcome class name
     *
     * @var string $name
     */
    public $name        =   '12';

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
    private $_types     =   array('1', '2');

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
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object Betting Type %s map() With Items Count ( %d ) Start.", $this->name, count($dataSet)));

        $hash = function($item) {
            return md5($item["objectFK"] . $item["type"] . $item["scope"]);
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
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object Betting Type %s reduce() With Items Count ( %d ) Start.", $this->name, count($this->_map)));

        foreach ($this->_map AS $hash => $set) {
            if (count($set) != 2) {
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
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object Betting Type %s Reset() With Items Count ( %d ) Start.", $this->name, count($this->_map)));

        $this->_map = array();

        $this->getName(array(), true);
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
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object Betting Type %s getInsertFields() With Items Count ( %d ) Start.", $this->name, count($this->_map)));

        try {
            $fParticipant = $objectAttributesData[0]["iparam"] != 0 ? $this->getParticipantIdImportId($objectAttributesData[0]["iparam"]) : array('Participant' => array('id' => 0));
            $sParticipant = $objectAttributesData[1]["iparam"] != 0 ? $this->getParticipantIdImportId($objectAttributesData[1]["iparam"]) : array('Participant' => array('id' => 0));

            if (empty($fParticipant) || empty($sParticipant)) {
                throw new Exception("Participant not found", 500);
            }

            return array(
                0   =>  array(
                    "import_id"             =>  $objectAttributesData[0]["id"],
                    "bet_id"                =>  $betId,
                    "event_participant_id"  =>  $fParticipant["Participant"]["id"],
                    "name"                  =>  $this->getName($objectAttributesData[0]),
                    "odd"                   =>  0,
                    "line"                  =>  "",
                    "order_id"              =>  $this->getOrder($this->getName($objectAttributesData[0]), $this->_types),
                ),
                1   =>  array(
                    "import_id"             =>  $objectAttributesData[1]["id"],
                    "bet_id"                =>  $betId,
                    "event_participant_id"  =>  $sParticipant["Participant"]["id"],
                    "name"                  =>  $this->getName($objectAttributesData[1]),
                    "odd"                   =>  0,
                    "line"                  =>  "",
                    "order_id"              =>  $this->getOrder($this->getName($objectAttributesData[1]), $this->_types),
                )
            );
        } catch (Exception $e) {
            var_dump($e);
            return array();
        }

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
    }
}