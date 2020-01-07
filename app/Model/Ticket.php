<?php
/**
 * Ticket Model
 *
 * Handles Ticket Data Source Actions
 *
 * @package    Tickets.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');
App::import('Model','Bet');
App::import('Model','Group');
App::uses('TicketListener', 'Event');

class Ticket extends AppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Ticket';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'date'      => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => true
        ),
        'user_id'       => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'odd'       => array(
            'type'      => 'decimal',
            'length'    => 8,
            'null'      => true
        ),
        'amount'    => array(
            'type'      => 'decimal',
            'length'    => 8,
            'null'      => true
        ),
        'return'    => array(
            'type'      => 'decimal',
            'length'    => 8,
            'null'      => true
        ),
        'type'      => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'status'    => array(
            'type'      => 'tinyint',
            'length'    => 4,
            'null'      => false
        ),
        'printed'   => array(
            'type'      => 'tinyint',
            'length'    => 1,
            'null'      => false
        ),
        'paid'      => array(
            'type'      => 'tinyint',
            'length'    => 1,
            'null'      => false
        )
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
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        )
    );

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = array('TicketPart');

    /**
     * Ticket type single value
     */
    const TICKET_TYPE_SINGLE        =  1;

    /**
     * Ticket type multi value
     */
    const TICKET_TYPE_MULTI         =  2;

    /**
     * Ticket condition unpaid value
     */
    const TICKET_CONDITION_UNPAID   =   0;

    /**
     * Ticket condition paid value
     */
    const TICKET_CONDITION_PAID     =   1;

    /**
     * Ticket status default value
     */
    const TICKET_STATUS_DEFAULT     =   0;

    /**
     * Ticket status cancelled value
     */
    const TICKET_STATUS_CANCELLED   =   -2;

    /**
     * Ticket status lost value
     */
    const TICKET_STATUS_LOST        =   -1;

    /**
     * Ticket status pending value
     */
    const TICKET_STATUS_PENDING     =   0;

    /**
     * Ticket status won value
     */
    const TICKET_STATUS_WON         =   1;

    /**
     * Paid ticket value
     */
    const PAID = 1;

    /**
     * Ticket types
     *
     * @var array
     */
    private static $ticketTypes = array();

    /**
     * Ticket statuses
     *
     * @var array
     */
    private static $ticketStatuses = array();

    /**
     * Ticket print types
     *
     * @var array
     */
    private static $ticketPrintTypes = array();

    /**
     * Constructor. Binds the model's database table to the object.
     *
     * @param bool $id    - Set this ID for this model on startup
     *                      can also be an array of options, see above.
     * @param null $table - Name of database table to use.
     * @param null $ds    - DataSource connection name.
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
        self::$ticketStatuses = array(
            null    =>  '---',
            1       =>  __('Won'),
            -2      =>  __('Cancelled'),
            -1      =>  __('Lost'),
            0       =>  __('Pending')
        );

        self::$ticketPrintTypes = array(
            null    =>  __('No'),
            false   =>  __('No'),
            1       =>  __('Yes')
        );

        self::$ticketTypes = array(
            null    =>  '---',
            1       =>  __('Single'),
            2       =>  __('Multi')
        );

        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new TicketListener());
    }

    /**
     * Returns admin tabs
     *
     * @param array $params - url params
     *
     * @return array
     */
    public function getTabs(array $params)
    {
        $tabs = parent::getTabs($params);
        unset($tabs['ticketsadmin_add']);
        unset($tabs['ticketsadmin_edit']);
        return $tabs;
    }

    /**
     * Place ticket
     *
     * @param $bets
     * @param $type
     * @param $odd
     * @param $stake
     * @param $serviceFee
     * @param $userId
     * @param $reservationTicketId
     */
    public function placeTicket($bets, $type, $odd, $stake, $serviceFee, $userId, $reservationTicketId = null)
    {
        $data['TicketPart'] = array();

        foreach ($bets as $bet) {
            $bet['BetPart']['bet_part_id'] = $bet['BetPart']['id'];
            $bet['BetPart']['event_id'] = $bet["Bet"]["event_id"];
            $bet['BetPart']["status"] = 0;
            unset($bet['BetPart']['id']);
            $data['TicketPart'][] = $bet['BetPart'];
        }

        $data['Ticket'] = array(
            'user_id'               =>  $userId,
            'reservation_ticket_id' =>  $reservationTicketId,
            'odd'                   =>  $odd,
            'amount'                =>  $serviceFee + $stake,
            'type'                  =>  $type,
            'service_fee'           =>  Configure::read('Settings.service_fee'),
            'return'                =>  $this->getMaxWinning($stake * $odd),
            'date'                  =>  gmdate('Y-m-d H:i:s'),
            'status'                =>  self::TICKET_STATUS_PENDING,
            'printed'               =>  0,
            'paid'                  =>  0
        );

        $this->create();

        $this->saveAll($data);

        $insertId = $this->id;
        $ticketsIds = CakeSession::read('TicketsIds');
        $ticketsIds[] = $insertId;

        $this->getEventManager()->dispatch(new CakeEvent('Model.Ticket.placeTicket', $this, array(
            'stake' =>  $serviceFee + $stake
        )));

        CakeSession::write('TicketsIds', $ticketsIds);

        return $insertId;
    }

    /**
     * Returns scaffold actions list
     *
     * @param CakeRequest $cakeRequest - cakeRequest object
     *
     * @return array
     */
    public function getActions(CakeRequest $cakeRequest)
    {
        $actions = parent::getActions($cakeRequest);

        if (CakeSession::read('Auth.User.group_id') == Group::OPERATOR_GROUP || CakeSession::read('Auth.User.group_id') == Group::ADMINISTRATOR_GROUP)
        {
            $actions = array_map(function($action){
                if($action['action'] == 'admin_edit' || $action['action'] == 'admin_delete') {
                    return null;
                }
                return $action;
            }, $actions);

            $actions[] = array(
                'name'          => __('Mark as paid', true),
                'controller'    => 'tickets',
                'action'        => 'admin_markAsPaid',
                'class'         => 'btn btn-mini btn-warning',
                'conditions'    =>  array(
                    'Ticket.paid' =>  'Unpaid'
                )
            );
        }

        return $actions;
    }

    public function getTicketsPartsByBetPartId($betPartId) {
        $options['conditions'] = array(
            'TicketPart.bet_part_id' => $betPartId
        );
        return $this->TicketPart->find('all', $options);
    }

    /**
     * Returns ticket with ticket part
     *
     * @param $id
     * @return array
     */
    public function getTicket($id)
    {
        $this->contain('TicketPart');

        return $this->find('first', array(
            'conditions'    =>  array(
                'Ticket.id'     =>  $id,
               // 'Ticket.status' =>  self::TICKET_STATUS_PENDING // @TODO jeigu per adminke keiti is WON i pending po to nebesikeicia
            )
        ));
    }

    /**
     * Returns all information associated with ticket
     *
     * @param int $ticketId - ticketId
     *
     * @return array
     */
    public function getAllTicketInformation($ticketId)
    {
        if (!is_numeric($ticketId)) {
            return array();
        }

        return $this->find('first', array(
            'contain'       =>  array(
                0   =>  'TicketPart',
                1   =>  'User'
            ),
            'conditions'    =>  array(
                'Ticket.id' => $ticketId
            )
        ));
    }

    /**
     * Marks ticket as paid
     *
     * @param $ticketId
     */
    public function setAsPaid($ticketId)
    {
        $ticket = $this->getItem($ticketId);

        $ticket['Ticket']['paid'] = self::PAID;

        $this->save($ticket);
    }

    /**
     * Returns is ticket cancelable
     *
     * @param $id
     * @return bool
     */
    public function isCancellable($id) {
        $Ticket = $this->find('first', array(
            'conditions'    =>  array(
                'Ticket.id'     =>  $id
            )
        ));

        if(empty($Ticket)) { return false; }

        if(CakeSession::read('Auth.User.Group.id') != Group::ADMINISTRATOR_GROUP) {
            $eventTime = new DateTime( $Ticket['Ticket']['date'] );
            $interval  = $eventTime->diff( new DateTime( gmdate('Y-m-d H:i:s') ) );
            if((int) $interval->format('%i') > 5) {
                return false;
            }
        }

        return true;
    }

    /**
     * Cancels ticket
     *
     * @param       $id          - TicketId
     * @param array $ticketParts - TicketPartsIds to be cancelled
     */
    public function cancel($id, array $ticketParts)
    {
        $Ticket = $this->find('first', array(
            'conditions'    =>  array(
                'Ticket.id'     =>  $id
            )
        ));

        $this->create();

        if (empty($Ticket) || (!empty($Ticket) && $Ticket['Ticket']['status'] == self::TICKET_STATUS_CANCELLED)) {
            return;
        }

        if(php_sapi_name() != "cli" && !$this->isCancellable($id)) {
            return;
        }

        $TicketPartIds = array_map(function($TicketPart){return $TicketPart['id'];}, $Ticket['TicketPart']);

        $diff = array_diff($TicketPartIds, $ticketParts);

        if (empty($diff)) {
            switch(CakeSession::read('Auth.User.Group.id')) {
                case Group::OPERATOR_GROUP:
                    $this->User->addFunds($Ticket['Ticket']['user_id'], -($Ticket['Ticket']['amount']));
                    break;
                default:
                    $this->User->addFunds($Ticket['Ticket']['user_id'], $Ticket['Ticket']['amount']);
                    break;
            }

            $Ticket['Ticket']['status'] = self::TICKET_STATUS_CANCELLED;

            $this->save($Ticket);

            foreach($Ticket['TicketPart'] AS $TicketPart) {
                $this->TicketPart->setStatus($TicketPart['id'], self::TICKET_STATUS_CANCELLED, false);
            }
        } else {
            foreach ($ticketParts AS $TicketPartId) {
                if (in_array($TicketPartId, $diff)) {
                    continue;
                }
                $this->TicketPart->setStatus($TicketPartId, Ticket::TICKET_STATUS_CANCELLED, false);
                $this->TicketPart->setOdd($TicketPartId, 1);
            }
        }
    }

    /**
     * Cancels ticket by bet part id
     *
     * @param $BetPartId
     * @param bool $checkIsCancellable
     */
    public function cancelByBetPartId($BetPartId, $checkIsCancellable = true) {
        $TicketParts = $this->TicketPart->find('all', array(
            'contain'       =>  array(),
            'fields'        =>  array('ticket_id'),
            'conditions'    =>  array('TicketPart.bet_part_id' => $BetPartId)
        ));

        if(is_array($TicketParts) && !empty($TicketParts)) {
            foreach($TicketParts AS $TicketPart) {
                $this->cancel($TicketPart['TicketPart']['ticket_id'], $checkIsCancellable);
            }
        }
    }

    /**
     * Update ticket status after
     * bet part status update
     *
     * @param $ticketId
     */
    public function updateStatusByBets($ticketId)
    {
        $Ticket = $this->getTicket($ticketId);

        if (empty($Ticket)) { return; }

        $newOdd = 1;
        $newStatus = null;
        $newReturn = null;

        $oldStatus = $Ticket['Ticket']['status'];
        $oldReturn = $Ticket['Ticket']['return'];

        $statuses = array();

        foreach ($Ticket['TicketPart'] as $ticketPart) {
            if($ticketPart['status'] != self::TICKET_STATUS_CANCELLED) {
                $newOdd *= $ticketPart['odd'];
            }
            $statuses[] =  $this->assignTicketStatusValue($ticketPart['status']);
        }

        $newStatus = $this->getTicketStatusAssoc($this->checkTicketPending($statuses), $this->checkTicketWon($statuses), $this->checkTicketLost($statuses), $newOdd);

        $newReturn = $this->getMaxWinning($Ticket['Ticket']['amount'] * $newOdd);

        $Ticket['Ticket']['odd']    = $newOdd;
        $Ticket['Ticket']['return'] = $newReturn;
        $Ticket['Ticket']['status'] = $newStatus;

        $this->save($Ticket);

        $this->getEventManager()->dispatch(new CakeEvent('Model.Ticket.updateStatus', $this, array(
            'Ticket'    =>  $Ticket['Ticket'],
            'newStatus' =>  $newStatus,
            'oldStatus' =>  $oldStatus,
            'oldReturn' =>  $oldReturn,
            'newReturn' =>  $newReturn
        )));
    }

    /**
     * Assigns ticket status value
     *
     * @param $ticketStatus
     * @return int
     */
    public function assignTicketStatusValue($ticketStatus)
    {
        switch($ticketStatus) {
            case self::TICKET_STATUS_CANCELLED:
                self::TICKET_STATUS_CANCELLED;
                break;
            case self::TICKET_STATUS_LOST:
                return self::TICKET_STATUS_LOST;
                break;
            case self::TICKET_STATUS_PENDING:
                return self::TICKET_STATUS_PENDING;
                break;
            case self::TICKET_STATUS_WON:
                return self::TICKET_STATUS_WON;
                break;
            default:
                return self::TICKET_STATUS_DEFAULT;
                break;
        }
    }

    /**
     * Returns statuses counts by type
     *
     * @param array $statuses
     * @return array
     */
    public function getTicketStatusTypesCount($statuses = array())
    {
        $results = array(
            self::TICKET_STATUS_LOST      =>  0,
            self::TICKET_STATUS_PENDING   =>  0,
            self::TICKET_STATUS_CANCELLED =>  0,
            self::TICKET_STATUS_WON       =>  0
        );

        foreach($statuses AS $status) {
            $results[$this->assignTicketStatusValue($status)]++;
        }

        return $results;
    }

    /**
     * Ticket is pending if meet following conditions:
     *  - All statuses in ticket are pending
     *  - All statuses are won except one which are pending
     *
     * @param array $statuses
     * @return bool
     */
    public function checkTicketPending($statuses = array())
    {
        $statusesCount = $this->getTicketStatusTypesCount($statuses);

        if(!isset($statusesCount[self::TICKET_STATUS_PENDING])) {
            return false;
        }

        if($statusesCount[self::TICKET_STATUS_PENDING] == count($statuses) ) {
            return true;
        }

        if(!isset($statusesCount[self::TICKET_STATUS_WON])) {
            return false;
        }

        if($statusesCount[self::TICKET_STATUS_WON] == ( count($statuses) - 1 ) &&  $statusesCount[self::TICKET_STATUS_PENDING] == 1) {
            return true;
        }

        return false;
    }

    /**
     * Ticket is won if meet following conditions:
     *  - All statuses is won
     *
     * @param array $statuses
     * @return bool
     */
    public function checkTicketWon($statuses = array())
    {
        $statusesCount = $this->getTicketStatusTypesCount($statuses);

        if(!isset($statusesCount[self::TICKET_STATUS_WON])) {
            return false;
        }

        if($statusesCount[self::TICKET_STATUS_WON] == count($statuses) ) {
            return true;
        }
        return false;
    }

    /**
     * Ticket is lost if meet following conditions:
     *  - Any one status is lost
     *
     * @param array $statuses
     * @return bool
     */
    public function checkTicketLost($statuses = array())
    {
        $statusesCount = $this->getTicketStatusTypesCount($statuses);

        if(!isset($statusesCount[self::TICKET_STATUS_LOST])) {
            return false;
        }

        if($statusesCount[self::TICKET_STATUS_LOST] >= 1) {
            return true;
        }

        return false;
    }

    /**
     * Returns ticket status from values
     *
     * @param $isPending
     * @param $isWon
     * @param $isLost
     * @param $odd
     * @return int
     */
    public function getTicketStatusAssoc($isPending, $isWon, $isLost, $odd)
    {
        if($isWon == true) {
            return self::TICKET_STATUS_WON;
        }

        if($isLost == true) {
            return self::TICKET_STATUS_LOST;
        }

        if($isPending == true) {
            return self::TICKET_STATUS_PENDING;
        }

        if( (int) $odd == 1 ) {
            return self::TICKET_STATUS_CANCELLED;
        }

        return self::TICKET_STATUS_DEFAULT;
    }


    /**
     * Returns max winning
     *
     * @param $winning
     * @return mixed
     */
    public function getMaxWinning($winning) {
        if ($winning > Configure::read('Settings.maxWin')) {
            $winning = Configure::read('Settings.maxWin');
        }
        return $winning;
    }

    /**
     * Returns tickets by odds
     *
     * @param $odd
     * @param int $status
     * @return array
     */
    public function getTicketsByOdd($odd, $status = self::TICKET_STATUS_PENDING)
    {
        $this->contain('User');

        return $this->find('all', array(
            'conditions'        =>  array(
                'Ticket.odd >=' => $odd,
                'Ticket.status' => $status
            ),
            'order' =>  array('Ticket.odd DESC')
        ));
    }

    /**
     * Returns tickets by stake
     *
     * @param $bigStake
     * @param int $status
     * @return array
     */
    public function getBigStakeTickets($bigStake, $status = self::TICKET_STATUS_PENDING)
    {
        return $this->find('all', array(
            'contain'       =>  array('User'),
            'conditions'    =>  array(
                'Ticket.amount >='  => $bigStake,
                'Ticket.status'     => $status
            ),
            'order'         =>  array('Ticket.amount DESC')
        ));
    }

    /**
     * Returns big winning tickets by status
     *
     * @param $bigWinning
     * @param int $ticketStatus
     * @return array
     */
    public function getBigWinningTickets($bigWinning, $ticketStatus = self::TICKET_STATUS_WON)
    {
        return $this->find('all', array(
            'contain'       =>  array('User'),
            'conditions'    =>  array(
                'Ticket.return >='  => $bigWinning,
                'Ticket.status'     => $ticketStatus
            ),
            'order' =>  array('Ticket.return DESC')
        ));
    }

    /**
     * Assigns ticket type
     *
     * @param $ticketType
     * @return mixed
     */
    public static function assignTicketType($ticketType)
    {
        if(!isset(self::$ticketTypes[$ticketType]))
            return $ticketType;

        return self::$ticketTypes[$ticketType];
    }

    /**
     * Assigns ticket status
     *
     * @param $ticketStatus
     * @return mixed
     * @deprecated use getTicketStatus()
     */
    public static function assignTicketStatus($ticketStatus)
    {
        if(!isset(self::$ticketStatuses[$ticketStatus]))
            return $ticketStatus;

        return self::$ticketStatuses[$ticketStatus];
    }

    /**
     * Assigns ticket status
     *
     * @param $ticketStatus
     */
    public function getTicketStatus($ticketStatus) {
        if(!isset(self::$ticketStatuses[$ticketStatus]))
            return $ticketStatus;

        return self::$ticketStatuses[$ticketStatus];
    }

    /**
     * Assigns printed type
     *
     * @param $ticketPrinted
     * @return mixed
     */
    public static function assignTicketPrinted($ticketPrinted)
    {
        if(!isset(self::$ticketPrintTypes[$ticketPrinted]))
            return $ticketPrinted;

        return self::$ticketPrintTypes[$ticketPrinted];
    }

    /**
     * Assigns paid type
     *
     * @param $ticketPaid
     * @param array $ticket
     * @return string
     */
    public function assignTicketPaid($ticketPaid, $ticket = array())
    {
        $ticketPaidTypes = array(null => 'Unpaid', false => 'Unpaid', 1 => 'Paid');

        if(!isset($ticketPaidTypes[$ticketPaid]))
            return $ticketPaid;

        if(!is_array($ticket) || empty($ticket))
            return $ticketPaidTypes[$ticketPaid];

        if($ticket['Ticket']['status'] == self::TICKET_STATUS_PENDING || $ticket['Ticket']['status'] == self::TICKET_STATUS_LOST || ($ticket['User']['group_id'] != Group::OPERATOR_GROUP && $ticket['User']['group_id'] != Group::ADMINISTRATOR_GROUP ) ) {
            return '---';
        }

        if($ticket['Ticket']['status'] == self::TICKET_STATUS_CANCELLED || $ticket['Ticket']['status'] == self::TICKET_STATUS_WON) {
            return $ticketPaidTypes[$ticketPaid];
        }

        return $ticketPaidTypes[$ticketPaid];
    }

    /**
     * Assigns addition ticket values
     *
     * @param array $data
     * @return array
     */
    public static function assignTicketData($data = array())
    {
        if(!is_array($data) || empty($data))
            return array();

        $model = array_keys($data[0]);
        $model = $model[0];

        foreach($data AS $i => $field)
        {
            foreach ($field[$model] as $key => $var) {
                if($key == 'type'){
                    $data[$i][$model][$key] = @self::assignTicketType($var);
                }elseif($key == 'status'){
                    $data[$i][$model][$key] = @self::assignTicketStatus($var);
                }elseif($key == 'printed'){
                    $data[$i][$model][$key] = @self::assignTicketPrinted($var);
                }elseif($key == 'paid'){
                    $data[$i][$model][$key] = @self::assignTicketPaid($var, $field);
                }

            }
        }

        return $data;
    }

    /**
     * Adds Bet to session
     *
     * @param $betPartId
     *
     * @return array
     */
    public function addBetToSession($betPartId)
    {
        /** @var Bet $BetModel */
        $BetModel = new Bet();

        $appendBet = false;

        $bets = $this->getBetsFromSession();

        $betsCount = count($bets);

        $newBet = $BetModel->BetPart->find('first', array('conditions' => array('BetPart.id' => $betPartId)));

        $event = $BetModel->Event->getItem($newBet['Bet']['event_id']);

        if ($event["Event"]["type"] == Event::EVENT_TYPE_LIVE) {
            if (Configure::read('Settings.reservation_ticket_mode') && !CakeSession::check('Auth.User.id')) {
                return array(
                    'status'    =>  false,
                    'msg'       =>  __('Reservation tickets is not available for live events.')
                );
            }
        }

        $BetModel->Event->League->contain('Sport');

        $league = $BetModel->Event->League->find('first', array(
                'contain'   =>  array('Sport'),
                'conditions' => array('League.id' => $event['Event']['league_id'])
            )
        );

        $newBet['Bet']['date'] = $event['Event']['date'];

        $newBet['Bet']['Sport'] = array(
            'name'      => $league['Sport']['name'],
            'min_bet'   => $league['Sport']['min_bet'],
            'max_bet'   => $league['Sport']['max_bet']
        );

        $newBet['Bet']['League'] = array(
            'min_bet'   => $league['League']['min_bet'],
            'max_bet'   => $league['League']['max_bet']
        );

        $newBet['League'] = $league['League'];

        $newBet['Event']  = $event['Event'];

        $newBet['Bet']['stake']     = Configure::Read('Settings.minBet');
        $newBet['Bet']['winning']   = $newBet['Bet']['stake'] * $newBet['BetPart']['odd'];

        if ($betsCount == Configure::read('Settings.maxBetsCount')) {
            return array(
                'status'    =>  false,
                'msg'       =>  __('Number of maximum bets in ticket is reached.')
            );
        }

        foreach ($bets as $index => $bet) {
            if ($bet['Bet']['event_id'] == $newBet['Bet']['event_id']) {
                $bets[$index] = $newBet;
                $appendBet = true;
            }
        }

        if($appendBet == false) { $bets[] = $newBet; }

        CakeSession::write('Ticket.bets', $bets);

        return array(
            'status'    =>  true,
            'msg'       =>  null
        );
    }

    /**
     * Synchronize session data with database
     * Before place ticket and other actions
     *
     */
    public function renewBetsFromSession()
    {
        /** @var Bet $BetModel */
        $BetModel = new Bet();
        $Bets = $this->getBetsFromSession();

        foreach($Bets AS $index => $bet) {
            $betPart = $BetModel->BetPart->find('first', array(
                'fields'        =>  array('id'),
                'contain'       =>  array(),
                'conditions'    => array('BetPart.id' => $bet["BetPart"]["id"])
            ));

            if (!empty($betPart)) {

                $Event = $BetModel->Event->getItem($bet['Bet']['event_id']);

                if (!empty($Event)) {
                    $Bets[$index]['Bet']['date'] = $Event['Event']['date'];
                    $Bets[$index]['Event'] = $Event['Event'];
                    $Bets[$index]['Event']['date'] = $Event['Event']['date'];
                } else {
                    $Bets[$index]['Bet']['date'] = '1970-01-01 00:00';
                    $Bets[$index]['Event']['date'] = '1970-01-01 00:00';
                }
            } else {
                $Bets[$index]['Bet']['date'] = '1970-01-01 00:00';
                $Bets[$index]['Event']['date'] = '1970-01-01 00:00';
            }
        }

        CakeSession::write('Ticket.bets', $Bets);

        return $Bets;
    }

    /**
     * Returns bets from session
     *
     * @return array|mixed
     */
    public function getBetsFromSession() {
        $bets = array();
        if (CakeSession::check('Ticket.bets')) {
            $bets = CakeSession::read('Ticket.bets');
        }
        return $bets;
    }

    /**
     * Flush bet session
     */
    public function flushBetSession() {
        $bets = $this->getBetsFromSession();
        foreach ($bets as $key => $bet) {
            unset($bets[$key]);
        }
        CakeSession::write('Ticket.bets', $bets);
        CakeSession::write('Ticket.stake', 1);
    }

    /**
     * Checks stake
     *
     * @return bool
     */
    public function checkStake()
    {
        $stake = CakeSession::read('Ticket.stake');

        if (!CakeSession::check('Ticket.stake')) {
            return array('status' => false, 'msg' => __('Lowest stake amount is %s %s', Configure::read('Settings.minBet'), Configure::read('Settings.currency')));
        }

        if (Configure::read('Settings.reservation_ticket_mode')) {
            return array('status' => true, 'msg' => '');
        }

        if ($stake > CakeSession::read('Auth.User.balance')) {
            if(CakeSession::read('Auth.User.Group.id') != Group::OPERATOR_GROUP) {
                return array('status' => false, 'msg' => __('You have not enough money'));
            }
        }

        if ((float) CakeSession::read('Auth.User.day_stake_limit') <= 0) {
            return array('status' => true, 'msg' => '');
        }

        $gmdate = gmdate('Y-m-d');

        $result = $this->find('first', array(
                'contain'   =>  array(),
                'fields'    =>  array(
                    'sum(Ticket.amount) AS amount'
                ),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'   =>  array($this->dateToStart($gmdate), $this->dateToEnd($gmdate)),
                    'Ticket.user_id'                =>  CakeSession::read('Auth.User.id')
                )
            )
        );

        if(is_null($result[0]['amount'])) {
            $result[0]['amount'] = 0;
        }

        if( (float) ($result[0]['amount'] + $stake) > CakeSession::read('Auth.User.day_stake_limit')) {
            return array('status' => false, 'msg' => __('You have reached maximum today\'s total stake limit which is %s %s', CakeSession::read('Auth.User.day_stake_limit'), Configure::read('Settings.currency')));
        }

        return array('status' => true, 'msg' => '');
    }

    /**
     * Checks stake limits and returns status with notice
     *
     * @param $stake
     * @return array
     */
    public function checkStakeLimits($stake)
    {
        $bets = $this->getBetsFromSession();

        foreach ($bets as $bet) {

            $betOdd = $this->find('first', array(
                    'contain'   =>  array(),
                    'fields'    =>  array(
                        'sum(Ticket.amount) AS amount'
                    ),
                    'conditions'    =>  array(
                        'Ticket.user_id'                =>  CakeSession::read('Auth.User.id')
                    ),
                    'joins'         =>  array(
                        array(
                            'table' => 'ticket_parts',
                            'alias' => 'TicketPart',
                            'type'  => 'inner',

                            'conditions' => array(
                                'Ticket.id = TicketPart.ticket_id',
                                'TicketPart.bet_part_id'  => $bet["BetPart"]["id"]
                            )
                        )
                    )
                )
            );

            if($stake < Configure::read('Settings.minBet')) {
                return array('status' => 'error', 'message' => __('Lowest stake amount is %s%s', Configure::read('Settings.minBet'), Configure::read('Settings.currency')));
            }
            if(($stake + $betOdd[0]['amount']) > Configure::read('Settings.maxBet')) {
                return array('status' => 'error', 'message' => __('Highest stake amount on %s is %s%s', $bet['BetPart']['name'], Configure::read('Settings.maxBet'), Configure::read('Settings.currency')));
            }
            if(!is_null(Configure::read('Settings.maxWining')) && $this->getWinning() > Configure::read('Settings.maxWining')) {
                return array('status' => 'error', 'message' => __('Highest winning amount is %s%s', Configure::read('Settings.maxWining'), Configure::read('Settings.currency')));
            }
            if (($bet['Bet']['League']['min_bet'] != 0) && ($bet['Bet']['League']['min_bet'] > $stake)) {
                return array('status' => 'error', 'message' => __('Lowest stake on %s is %d %s', $bet['Bet']['name'], $bet['Bet']['League']['min_bet'], Configure::read('Settings.currency')));
            }

            if (($bet['Bet']['League']['max_bet'] != 0) && ($bet['Bet']['League']['max_bet'] < $stake)) {
                return array('status' => 'error', 'message' => __('Highest stake on %s is %s %s', $bet['Bet']['name'], $bet['Bet']['League']['max_bet'], Configure::read('Settings.currency')));
            }

            if (($bet['Bet']['Sport']['min_bet'] != 0) && ($bet['Bet']['Sport']['min_bet'] > $stake)) {
                return array('status' => 'error', 'message' => __('Lowest stake on %s is %s %s', $bet['Bet']['name'], $bet['Bet']['Sport']['min_bet'], Configure::read('Settings.currency')));
            }
            if (($bet['Bet']['Sport']['max_bet'] != 0) && ($bet['Bet']['Sport']['max_bet'] < $stake)) {
                return array('status' => 'error', 'message' => __('Highest stake on %s is %s %s', $bet['Bet']['name'], $bet['Bet']['Sport']['max_bet'], Configure::read('Settings.currency')));
            }
        }

        return array('status' => 'ok', 'msg' => null);
    }

    /**
     * Sets stake
     *
     * @param $stake
     * @param int $betId
     * @return bool
     */
    public function setStake($stake, $betId = 0)
    {
        $stake      = str_replace(',', '.', $stake);
        $stake      = (float) $stake;

        if ($stake < Configure::read('Settings.minBet') || $stake > Configure::read('Settings.maxBet')) {
            CakeSession::write('Ticket.stake', 0);
            return false;
        }

        if (!is_null(Configure::read('Settings.maxWining')) && $this->getWinning() > Configure::read('Settings.maxWining')) {
            return false;
        }

        if ($betId == 0) {
            CakeSession::write('Ticket.stake', (string) $stake);
        } else {
            $bets = $this->getBetsFromSession();
            foreach ($bets as $key => $bet) {
                if ($bet['BetPart']['id'] == $betId) {
                    $bets[$key]['Bet']['stake'] = $stake;
                    $winning = $this->getMaxWinning($stake * $bets[$key]['BetPart']['odd']);
                    $bets[$key]['Bet']['winning'] = $winning;
                }
            }
            CakeSession::write('Ticket.bets', $bets);
        }

        return true;
    }

    /**
     * Returns ticket type
     *
     * @return int|mixed
     */
    public function getTicketType()
    {
        $bets = $this->getBetsFromSession();

        if (count($bets) == 1 && !CakeSession::check('Ticket.type')) {
            CakeSession::write('Ticket.type', 1);
            return 1;
        }

        //we have type set
        if (CakeSession::check('Ticket.type')) {
            // 1 -> 2 change to multi
            if (count($bets) == 1) {
                CakeSession::write('Ticket.type', 1);
                return 1;
            }
            if (count($bets) == 2) {
                // nebutinai switchiname, kadangi i single leis pereiti
                // tik tada kai bus nemaziau 3 betu, o mes to nenorime.
                //CakeSession::write('Ticket.type', 2);
                //return 2;
            }
            //we don\'t allow multiple single bets, change to multi
            if ((Configure::read('Settings.allowMultiSingleBets') == 0) && (CakeSession::read('Ticket.type') == 1)) {
                CakeSession::write('Ticket.type', 2);
                return 2;
            }
        }

        //return multibet
        //CakeSession::write('Ticket.type', 2);
        return CakeSession::read('Ticket.type');
    }

    /**
     * Checks betting events dates
     * for already started events
     *
     * @return array - started events list
     */
    public function checkDates()
    {
        $started    =   array();
        $bets       =   $this->getBetsFromSession();

        foreach ($bets as $bet) {

            $utc_str = gmdate("M d Y H:i:s", time());

            $utc = strtotime($utc_str);

            if (strtotime($bet['Bet']['date']) < $utc && $bet["Event"]["type"] != 2) {
                $started[] = $bet;
            }
            
            if ($bet["Event"]["active"] == false) {
                $started[] = $bet;
            }
        }

        return $started;
    }

    public function checkLive()
    {
        $started    =   array();
        $bets       =   $this->getBetsFromSession();

        foreach ($bets AS $bet) {
            if ($bet["Event"]["type"] != 2) {
                continue;
            }

            if (Configure::read('Settings.reservation_ticket_mode') && !CakeSession::check('Auth.User.id')) {
                $started[] = $bet;
            } else {
                $betPart = $this->TicketPart->BetPart->getItem($bet["BetPart"]["id"]);

                if (empty($betPart) || $betPart["BetPart"]["odd"] != $bet["BetPart"]["odd"]) {
                    $started[] = $bet;
                }
            }
        }

        return $started;
    }

    public function checkSuspended()
    {
        $started    =   array();
        $bets       =   $this->getBetsFromSession();

        foreach ($bets as $bet) {
            if ($bet['BetPart']['suspended'] == 1) {
                $started[] = $bet;
            }
        }

        return $started;
    }

    public function checkBetBeforeEventStartDate()
    {
        $started    =   array();
        $bets       =   $this->getBetsFromSession();
        $val        =   Configure::read("Settings.betBeforeEventStartDate");
        $val        =   !is_numeric($val) ? 0 : intval($val);

        if ($val == 0) {
            return array();
        }

        foreach ($bets as $bet) {

            $utc_str    =   gmdate("M d Y H:i:s", time());
            $utc        =   strtotime($utc_str);

            if (strtotime('-'. $val .' minutes', strtotime($bet['Bet']['date'])) < $utc) {
                $started[] = $bet;
            }
        }

        return $started;
    }

    /**
     * Returns stake
     *
     * @return mixed
     */
    public function getStake() {
        return CakeSession::read('Ticket.stake');
    }

    /**
     * Returns Odds Count
     *
     * @return int
     */
    public function getOdds() {
        $totalOdds = 1;
        $bets = $this->getBetsFromSession();

        foreach ($bets as $bet) {
            if(isset($bet['BetPart'])) {
                $totalOdds *= $bet['BetPart']['odd'];
            }
        }

        return $totalOdds;
    }

    /**
     * Returns winning amount
     *
     * @param int $type
     * @param int $odd
     * @param int $stake
     * @return int|mixed
     */
    public function getWinning($type = null, $odd = null, $stake = null)
    {
        if (is_null($type)) {
            $type = $this->getTicketType();
        }

        if (is_null($odd)) {
            $odd =  $this->getOdds();
        }

        if (is_null($stake)) {
            $stake = $this->getStake();
        }

        $winning = 0;

        if ($type == 1) {
            $bets = $this->getBetsFromSession();
            foreach ($bets as $bet) {
                $winning += $bet['BetPart']['odd'] * $stake;
            }
        } else if ($type == 2) {
            $winning = $odd * $stake;
        }

        return $this->getMaxWinning($winning);
    }

    /**
     * @return array
     */
    public function addTicketCheck()
    {
        $this->renewBetsFromSession();

        if (!CakeSession::check('Ticket.bets')) {
            return array('status' => false, 'msg' => null);
        }

        if (!CakeSession::check('Auth.User.id') && Configure::read('Settings.reservation_ticket_mode') != 1) {
            return array('status' => false, 'msg' => __('Please login or register to place the ticket', true));
        }

        if(CakeSession::read('Auth.User.allow_betslip') != 1 && Configure::read('Settings.reservation_ticket_mode') != 1) {
            return array('status' => false, 'msg' => __('Sorry, you are not allowed to place tickets. Please contact support.', true));
        }

        if (count($this->getBetsFromSession()) < Configure::read('Settings.minBetsCount')) {
            return array('status' => false, 'msg' => __('Please select at least %d events to place the ticket', Configure::read('Settings.minBetsCount')));
        }

        $stakeLimits = $this->checkStakeLimits(CakeSession::read('Ticket.stake'));

        if ($stakeLimits['status'] != "ok") {
            return array('status' => false, 'msg' => $stakeLimits['message']);
        }

        $resultCheckStake = $this->checkStake();

        if (!$resultCheckStake['status']) {
            return array('status' => false, 'msg' => $resultCheckStake['msg']);
        }

        $checkDates  = $this->checkDates();

        if (!empty($checkDates)) {
            return array('status' => false, 'msg' => __('Following event(s) are already started or no longer active. <br /> Please amend them from BetSlip:'), 'data' => $checkDates);
        }

        $checkSuspended  = $this->checkSuspended();

        if (!empty($checkSuspended)) {
            return array('status' => false, 'msg' => __('Selected event(s) bet(s) are suspended. <br /> Please amend them from BetSlip:'), 'data' => $checkSuspended);
        }

        $checkLive  = $this->checkLive();

        if (!empty($checkLive)) {
            if (Configure::read('Settings.reservation_ticket_mode') && !CakeSession::check('Auth.User.id')) {
                return array('status' => false, 'msg' => __('Live events are not supported for reservation tickets. <br /> Please amend them from BetSlip:'), 'data' => $checkLive);
            }
            return array('status' => false, 'msg' => __('Selected live event(s) bet(s) are changed. <br /> Please amend them from BetSlip or update:'), 'data' => $checkLive);
        }

        $checkAllowDate = $this->checkBetBeforeEventStartDate();

        if (!empty($checkAllowDate)) {
            return array('status' => false, 'msg' => __('Selected event(s) bet(s) are about to start shortly. <br /> Please amend them from BetSlip:'), 'data' => $checkAllowDate);
        }

        return array('status' => true, 'msg' => null);
    }

    /**
     * Admin edit fields
     *
     * @return mixed
     */
    public function getEdit($data)
    {
        $fields = array(
            'Ticket.date'       => $this->getFieldHtmlConfig('date'),
            'Ticket.user_id'    => array(),
            'Ticket.odd'        => $this->getFieldHtmlConfig('number'),
            'Ticket.amount'     => $this->getFieldHtmlConfig('number'),
            'Ticket.return'     => $this->getFieldHtmlConfig('number'),
            'Ticket.type'       => $this->getFieldHtmlConfig('select', array('options' => self::$ticketTypes)),
            'Ticket.status'     => $this->getFieldHtmlConfig('select', array('options' => self::$ticketStatuses)),
            'Ticket.printed'    => $this->getFieldHtmlConfig('switch',
                array(
                    'id'    => 'TicketPrinted',
                    'label' => __('Printed'),
                    'style' => 'margin-top:15px;'
                )
            ),
        );

        $fields = $this->getBelongings($fields);

        return $fields;
    }

    public function getPagination()
    {
        $parent = parent::getPagination();
        $parent["contain"] = array('User', 'TicketPart');
        return $parent;
}

    public function getAdminIndexSchema()
    {
        return array_merge( parent::getAdminIndexSchema(), array('User.username'));
    }

    /**
     * Returns search fields
     *
     * @return array|mixed
     */
    public function getSearch()
    {
        $fields = array(
            'Ticket.id'         => $this->getFieldHtmlConfig('number', array('label' => __('Ticket ID'))),
            'Ticket.amount'         => array('type' => 'hidden'),
            'Ticket.amount_from'    => $this->getFieldHtmlConfig('currency', array('style' => 'width: 123px', 'label' => __('Amount From'))),
            'Ticket.amount_to'      => $this->getFieldHtmlConfig('currency', array('style' => 'width: 123px', 'label' => __('Amount To'))),

            'Ticket.type'       => $this->getFieldHtmlConfig('select', array('options' => self::$ticketTypes)),

            'Ticket.return'         => array('type' => 'hidden'),
            'Ticket.return_from'    => $this->getFieldHtmlConfig('currency', array('style' => 'width: 123px', 'label' => __('Potential Winning From'))),
            'Ticket.return_to'      => $this->getFieldHtmlConfig('currency', array('style' => 'width: 123px', 'label' => __('Potential Winning To'))),

            'Ticket.date'           => array('type' => 'hidden'),
            'Ticket.date_from'      => $this->getFieldHtmlConfig('date'),
            'Ticket.date_to'        => $this->getFieldHtmlConfig('date'),

            'Ticket.odd'         => array('type' => 'hidden'),
            'Ticket.odd_from'    => $this->getFieldHtmlConfig('number', array('label' => __('Odd From'))),
            'Ticket.odd_to'      => $this->getFieldHtmlConfig('number', array('label' => __('Odd To'))),

            'Ticket.status'     => $this->getFieldHtmlConfig('select', array('options' => self::$ticketStatuses))
        );

        if(CakeSession::read('Auth.User.group_id') != Group::ADMINISTRATOR_GROUP) {
            unset($fields['Ticket.user_id']);
        }

        return $fields;
    }

    /**
     * Returns search fields
     *
     * @param array $data - fields data array
     *
     * @return array
     */
    public function getSearchFields(array $data)
    {
        return array(
            'Ticket.id'   =>  array(
                'Ticket.id = ?'   =>  array(
                    0   =>  'Ticket.id'
                )
            ),

            'Ticket.date'    =>  array(
                'DATE(`Ticket.date) BETWEEN ? AND ?' => array(
                    0   =>  'Ticket.date_from',
                    1   =>  'Ticket.date_to'
                )
            ),

            'Ticket.amount'    =>  array(
                'Ticket.amount BETWEEN ? AND ?' => array(
                    0   =>  'Ticket.amount_from',
                    1   =>  'Ticket.amount_to'
                )
            ),

            'Ticket.return'    =>  array(
                'Ticket.return BETWEEN ? AND ?' => array(
                    0   =>  'Ticket.return_from',
                    1   =>  'Ticket.return_to'
                )
            ),

            'Ticket.odd'    =>  array(
                'Ticket.odd BETWEEN ? AND ?' => array(
                    0   =>  'Ticket.odd_from',
                    1   =>  'Ticket.odd_to'
                )
            ),

            'Ticket.type'    =>  array(
                'Ticket.type = ? ' => array(
                    0   =>  'Ticket.type'
                )
            ),

            'Ticket.status'    =>  array(
                'Ticket.status = ? ' => array(
                    0   =>  'Ticket.status'
                )
            )
        );
    }
}