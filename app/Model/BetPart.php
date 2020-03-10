<?php
/**
 * BetPart Model
 *
 * Handles BetPart Data Source Actions
 *
 * @package    BetParts.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');
App::uses('UString', 'Utility');

class BetPart extends AppModel
{

    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'BetPart';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'                    =>  array(
            'type'      => 'bigint',
            'length'    => 20,
            'null'      => false
        ),
        'import_id'             =>  array(
            'type'      => 'bigint',
            'length'    => 255,
            'null'      => false
        ),
        'bet_id'                =>  array(
            'type'      => 'bigint',
            'length'    => 20,
            'null'      => false
        ),
        'event_participant_id'  =>  array(
            'type'      => 'bigint',
            'length'    => 20,
            'null'      => false
        ),
        'name'                  =>  array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => false
        ),
        'odd'                   =>  array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => false
        ),
        'line'                  =>  array(
            'type'      => 'string',
            'length'    => 10,
            'null'      => true
        ),
        'order_id'              =>  array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array('Bet');

    public $hasMany = array('TicketPart');

    /**
     * BetParts container
     *
     * @var array
     */
    protected $betParts = array();

    /**
     * Inserts Single Bet Part
     *
     * @param null $betPartImportId
     * @param $betPartName
     * @param $betId
     * @param $betPartOdd
     * @param $line
     * @param array $importedBet
     * @return array|bool|int|mixed|string
     */
    public function insertBetPart($betPartImportId = null, $betPartName, $betId, $betPartOdd, $line, $importedBet = array(), $updateOdd = true,$header=null,$handicap=null)
    {
        $this->create();

        if(isset($importedBet['BetPart']) && is_array($importedBet['BetPart']) && !empty($importedBet['BetPart'])) {
            foreach($importedBet['BetPart'] AS $BetPart) {
                if($BetPart['import_id'] == $betPartImportId) {
                    $this->id = $BetPart['id'];
                }
            }
        }
        $betPartName=str_replace("Draw","X",$betPartName);

        $BetPart = array(
            'BetPart'   =>  array(
                'import_id' =>  $betPartImportId,
                'name'      =>  $betPartName,
                'bet_id'    =>  $betId,
                'odd'       =>  $betPartOdd,
                'line'      =>  $line,
                'header'       =>  $header,
                'handicap'      =>  $handicap
            )
        );

        if (!$updateOdd) {
            unset($BetPart["BetPart"]["odd"]);
        }

        $BetPart['BetPart']['order_id'] = $this->getBetPartOrderId($BetPart, $importedBet['Bet']['name']);

        $this->save($BetPart);

        return $this->id;
    }

    /**
     * Assigns bet parts to events
     *
     * @param array $events
     * @return array
     */
    public function assignBetPartsToEvents($events = array())
    {
        foreach ($events AS $eventIndex => $event) {

            if (!isset($event['Bet'])) { continue; }

            foreach ($event['Bet'] AS $betIndex => $bet)
            {
                $parts = $this->getBetParts($bet['id']);

                if (empty($parts)) {
                    unset($events[$eventIndex]['Bet'][$betIndex]);
                    continue;
                }
                $events[$eventIndex]['Bet'][$betIndex]['BetPart'] = $parts;
            }

            usort($events[$eventIndex]['Bet'], array($this->Bet, 'sortBet'));
        }

        return $events;
    }

    /**
     * Returns bet parts by betId
     * If defined bet type also orders
     *
     * @param int   $betId  -
     * @param array $fields -
     *
     * @return array
     */
    public function getBetParts($betId, $fields = array('id', 'import_id', 'bet_id', 'name', 'odd', 'line', 'order_id', 'suspended'))
    {
        return $this->find('all', array(
            'recursive' =>  -1,
            'fields'    =>  $fields,
            'conditions'    =>  array(
                'BetPart.bet_id'    => $betId,
                'BetPart.state'    => 'active',
                'BetPart.odd >='    => 1
            ),
            'order'     =>  'BetPart.order_id ASC'
        ));
    }

    /**
     * Returns bets ids by bet parts ids
     *
     * @param $betPartsIds
     * @return array
     */
    public function getBetsIds($betPartsIds) {
        $options['conditions'] = array(
            'BetPart.id' => $betPartsIds
        );
        $options['fields'] = array(
            'BetPart.id',
            'BetPart.bet_id'
        );
        $options['group'] = 'BetPart.bet_id';
        return $this->find('list', $options);
    }

    /**
     * Returns bet parts ids by bet id
     *
     * @param $betId
     * @return array
     */
    public function getBetPartsIds($betId)
    {
        return $this->find('list', array(
            'conditions'    =>  array(
                'BetPart.bet_id'    =>  $betId
            ),
            'fields'    =>  array(
                0   =>  'BetPart.id',
                1   =>  'BetPart.id'
            )
        ));
    }

    /**
     * Returns bet part order
     *
     * @param $BetPartName
     * @param $betType
     *
     * @return int|mixed
     */
    public function getBetPartOrderId($BetPartName, $betType)
    {
        $options  = array('trim' => true, 'strtolower' => true);
        $betType  = UString::clean($betType, $options);

        if(is_array($BetPartName) && !empty($BetPartName)) {
            $BetPartName = $BetPartName['BetPart']['name'];
        }

        switch( $betType ) {
            case '1x2':
                $betPartOrder = array(1, 'x', 2);
                break;
            case 'europeanhandicap':
                $betPartOrder = array(1, 'x', 2);
                break;
            case 'firstteamtoscore':
                $betPartOrder = array(1, 'no goal', 2);
                break;
            case '12':
                $betPartOrder = array(1, 2);
                break;
            case 'under/over':
                $betPartOrder = array('under', 'over');
                break;
            case 'under/over1stperiod':
                $betPartOrder = array('under', 'over');
                break;
            case 'under/over2stperiod':
                $betPartOrder = array('under', 'over');
                break;
            case 'doublechance':
                $betPartOrder = array('1x',  '12', 'x2');
                break;
            case 'doublechancehalftime':
                $betPartOrder = array('1x',  '12', 'x2');
                break;
            case 'correctscore':
                $betPartOrder = array();
                break;
            case 'ht/ft':
                $betPartOrder = array('1/1', '2/1', 'x/1', '1/x', '2/x', '1/2', 'x/2', '2/2', 'x/x');
                break;
            case 'bothteamstoscore':
                $betPartOrder = array('yes', 'no');
                break;
            case 'awayteamtoscore':
                $betPartOrder = array('yes', 'no');
                break;
            case 'hometeamtoscore':
                $betPartOrder = array('yes', 'no');
                break;
            case 'odd/even':
                $betPartOrder       =   array('odd', 'even');
                break;
            case 'odd/evenhalftime':
                $betPartOrder       =   array('odd', 'even');
                break;
            case '1stperiodwinner':
                $betPartOrder = array(1, 'x', 2);
                break;
            case '2ndperiodwinner':
                $betPartOrder = array(1, 'x', 2);
                break;
            case 'highestscoringhalf':
                $betPartOrder = array('first half', 'both halves the same', 'second half');
                break;
            default:
                return 0;
                break;
        }

        return array_search( strtolower( $BetPartName ), $betPartOrder) + 1;
    }

    public function setSuspended($id, $value)
    {
        $item2 = $this->getItem($id);

        if (empty($item2)) {
            return false;
        }
        $item2["BetPart"]["suspended"] = $value == "false" ? 0 : 1;

        $this->save($item2);
    }

    public function setOdd($id, $value)
    {
        $item2 = $this->getItem($id);

        if (empty($item2)) {
            return false;
        }
        $item2["BetPart"]["odd"] = floatval($value);

        $this->save($item2);
    }
}