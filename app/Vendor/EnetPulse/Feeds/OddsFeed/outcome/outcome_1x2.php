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

class EnetPulse_ObjectParser_outcome_1x2 extends EnetPulse_ObjectParser_OutcomeTypeProcessor
{
    /**
     * Outcome class name
     *
     * @var string $name
     */
    public $name        =   '1x2';

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
    private $_types     =   array('1', 'x', '2');

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
            return md5($item["objectFK"] . $item["type"] . $item["scope"]);
        };

        foreach ($dataSet AS $item) {
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
            if (count($set) != 3) {
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
        try {
            EnetPulseShell::getInstance()->out(__("OddsFeed :: Object Betting Type %s getInsertFields() With Items Count ( %d ) Start.", $this->name, count($this->_map)));

            $fParticipant = $objectAttributesData[0]["iparam"] != 0 ? $this->getParticipantIdImportId($objectAttributesData[0]["iparam"]) : array('Participant' => array('id' => 0));
            $sParticipant = $objectAttributesData[1]["iparam"] != 0 ? $this->getParticipantIdImportId($objectAttributesData[1]["iparam"]) : array('Participant' => array('id' => 0));
            $tParticipant = $objectAttributesData[2]["iparam"] != 0 ? $this->getParticipantIdImportId($objectAttributesData[2]["iparam"]) : array('Participant' => array('id' => 0));

            if (empty($fParticipant) || empty($sParticipant) || empty($tParticipant)) {
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
                ),
                2   =>  array(
                    "import_id"             =>  $objectAttributesData[2]["id"],
                    "bet_id"                =>  $betId,
                    "event_participant_id"  =>  $tParticipant["Participant"]["id"],
                    "name"                  =>  $this->getName($objectAttributesData[2]),
                    "odd"                   =>  0,
                    "line"                  =>  "",
                    "order_id"              =>  $this->getOrder($this->getName($objectAttributesData[2]), $this->_types),
                )
            );
        } catch (Exception $e) {
            var_dump($e);
            // TODO : Log
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

    /**
     * Returns Bet Name By Participant
     *
     * @param array $item
     * @param bool  $flush
     *
     * @return bool
     * @throws Exception
     */
    public function getName(array $item, $flush = false)
    {
        $key    =   md5('getName' . $this->name . __FUNCTION__);

        if ($flush == true) {
            $this->tmpDelete($key);
            return true;
        }

        $hash  =   md5($item["id"]);

        if (!$this->tmpExists($key) || (strtolower($item["subtype"]) == "draw")) {
            if (!$this->tmpExists($key)) {
                $this->tmpAdd($key, array($hash => $this->_types[1]));
            }
            return $this->_types[1];
        }

        switch(parent::getName($item, $flush)) {
            case 1:
                return $this->_types[0];
                break;
            case 2:
                return $this->_types[2];
                break;
            case 12:
                return $this->_types[1];
            default:
                throw new Exception(sprintf("Unknown getName() result. Got ( %s ) expected one of: ", parent::getName($item, $flush), var_export($this->_types)), 500);
                break;
        }
    }
}