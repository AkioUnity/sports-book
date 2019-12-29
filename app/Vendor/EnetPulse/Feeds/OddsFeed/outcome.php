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
App::uses('Event', 'Model');
require_once(str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__))) . DS . 'outcome' . DS . 'outcome.php');

class EnetPulse_ObjectParser_outcome
{
    /**
     * Class name
     *
     * @var string $name
     */
    public $name = 'outcome';
    /**
     * Bet Model
     *
     * @var Bet $_Bet
     */
    private $_Bet;

    /**
     * Event Model
     *
     * @var Event $_Event
     */
    private $_Event;

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
     * Bet types map
     *
     * @var array $_types
     */
    private $_types = array(
        '1x2'       =>  array('object' => null, 'type' => Bet::BET_TYPE_MATCH_RESULT, 'available' => true),
        '12'        =>  array('object' => null, 'type' => Bet::BET_TYPE_MATCH_WINNER, 'available' => true),
//        'oe'        =>  array('object' => null, 'type' => Bet::BET_TYPE_ODD_EVEN, 'available' => true),
        'ou'        =>  array('object' => null, 'type' => Bet::BET_TYPE_UNDER_OVER, 'available' => true),
        'dc'        =>  array('object' => null, 'type' => Bet::BET_TYPE_DOUBLE_CHANCE, 'available' => true),
        'cs'        =>  array('object' => null, 'type' => Bet::BET_TYPE_CORRECT_SCORE, 'available' => false),
        'ah'        =>  array('object' => null, 'available' => false),
        '1x2_hc'    =>  array('object' => null, 'available' => false),
        'win'       =>  array('object' => null, 'available' => false),
        'gs'        =>  array('object' => null, 'available' => false),
//        'ht_ft'     =>  array('object' => null, 'type' => Bet::BET_TYPE_HT_FT, 'available' => false),
        'ng'        =>  array('object' => null, 'available' => false),
        'smg'       =>  array('object' => null, 'available' => false),
        'tng'       =>  array('object' => null, 'available' => false),
        'nc'        =>  array('object' => null, 'available' => false),
        /** Draw No Bet */
        'dnb'       =>  array('object' => null, 'available' => false),
        /** Both to score */
//        'bts'       =>  array('object' => null, 'type' => Bet::BET_TYPE_BOTH_TEAMS_TO_SCORE, 'available' => false),
        /** Win to nil */
        'wtn'       =>  array('object' => null, 'available' => false),
        /** Each Way */
        'ew'        =>  array('object' => null, 'available' => false)
    );

    /**
     * Initializes required classes, data
     *
     * @param array $objectDataAttributes
     */
    public function __construct(array $objectDataAttributes)
    {
        $this->_objectDataAttributes    =   $objectDataAttributes;

        $this->_Bet                     =   new Bet();

        $this->_Event                   =   new Event();

        $this->_schema                  =   array_keys($this->_Bet->schema());
    }

    /**
     * Updates, inserts data
     *
     * @param array $objectAttributesData
     *
     * @return array
     */
    public function parseObject(array $objectAttributesData)
    {
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object %s Import Start.", $this->name));

        $this->mapObjects($objectAttributesData);

        $this->reduceObjects();

        $this->saveObjects();

        $this->_map = array();
    }

    /**
     * Maps objects
     *
     * @param array $items
     *
     * @return void
     */
    public function mapObjects(array $items)
    {
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object %s Map Start.", $this->name));

        foreach ($items AS $item)  {
            $this->_map[$item["type"]][] = $item;
        }
    }

    /**
     * Reduces objects
     *
     * @return void
     */
    public function reduceObjects() {
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object %s Reduce Start.", $this->name));
        foreach(array_keys($this->_map) AS $bettingType) {
            if (isset($this->_types[$bettingType]) && $this->_types[$bettingType]['available'] === true) {
                EnetPulseShell::getInstance()->out(__("OddsFeed :: Object %s Reduce Betting Type ( %s ) Process Start .", $this->name, $bettingType));
                if (is_null($this->_types[$bettingType]['object'])) {
                    $fileName   =   $this->name . "_" .  $bettingType . '.php';
                    $className  =   __CLASS__ . "_" . $bettingType;
                    require_once(str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__))) . DS . $this->name . DS . $fileName );
                    $this->_types[$bettingType]['object'] = new $className();
                }

                                            $this->_types[$bettingType]['object']->map($this->_map[$bettingType]);
                $this->_map[$bettingType] = $this->_types[$bettingType]['object']->reduce();
            } else {
                EnetPulseShell::getInstance()->out(__("OddsFeed :: Object %s Reduce Betting Type ( %s ) Not Available For Process, Skipping.", $this->name, $bettingType));
                unset($this->_map[$bettingType]);
            }
        }
    }

    /**
     * Saves objects
     *
     * @return void
     */
    public function saveObjects()
    {
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object %s Save Objects Start.", $this->name));
        EnetPulseShell::getInstance()->out(__("OddsFeed :: Object %s Save Objects Total ( %d ) For Process.", $this->name, count($this->_map)));

        foreach ($this->_map AS $bettingType => $betItems) {
            EnetPulseShell::getInstance()->out(__("OddsFeed :: Object %s For Save Object Type ( %s ) Total Count ( %d ) To Process.", $this->name, $bettingType, count($this->_map)));
            foreach ($betItems AS $betItemSet) {
                $this->_Bet->create();
                $this->_Bet->BetPart->create();

                $BetParts = $this->_Bet->BetPart->find('all', array(
                    'contain'       =>  array(),
                    'fields'        =>  array(),
                    'conditions'    =>  array(
                        'BetPart.import_id'    =>  $this->_types[$bettingType]['object']->getImportIds($betItemSet)
                    )
                ));

                if (empty($BetParts)) {
                    $Event = $this->_Event->find('first', array(
                        'fields'        =>  array('Event.id'),
                        'contain'       =>  array(),
                        'conditions'    =>  array(
                            "Event.import_id" =>  $this->_types[$bettingType]['object']->getEventImportId($betItemSet)
                        )
                    ));

                    if (!isset($Event["Event"]["id"])) {
                        continue;
                    }

                    $Bet = $this->_Bet->save(array(
                            "import_id" =>  0,
                            "event_id"  =>  $Event["Event"]["id"],
                            "name"      =>  $this->_types[$bettingType]["type"],
                            "type"      =>  $this->_types[$bettingType]["type"],
                            "scope"     =>  $this->_types[$bettingType]['object']->getScope($betItemSet),
                            "pick"      =>  0,
                        )
                    );

                    try {
                        $InsertFields = $this->_types[$bettingType]['object']->getInsertFields(
                            $Bet["Bet"]["id"],
                            $betItemSet
                        );

                        if (!empty($InsertFields)) {
                            $this->_Bet->BetPart->saveAll($InsertFields);
                        }
                    } catch(Exception $e) {
                        var_dump($e);
                        // TODO : Log
                        EnetPulseShell::getInstance()->out($e->getMessage());
                    }

                }

                $this->_types[$bettingType]['object']->reset();
            }
        }
    }
}