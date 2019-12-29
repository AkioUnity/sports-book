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

class ReservationTicket extends AppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'ReservationTicket';

    public $table = 'reservation_tickets';

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
        'ticket_id' => array(
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
        'Ticket' => array(
            'className' => 'Ticket',
            'foreignKey' => 'ticket_id',
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
    public $hasMany = array('ReservationTicketPart');

    /**
     * Ticket type single value
     */
    const TICKET_TYPE_SINGLE        =  1;

    /**
     * Ticket type multi value
     */
    const TICKET_TYPE_MULTI         =  2;

    /**
     * Ticket types
     *
     * @var array
     */
    private static $ticketTypes = array();

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
        self::$ticketTypes = array(
            null    =>  '---',
            1       =>  __('Single'),
            2       =>  __('Multi')
        );

        parent::__construct($id, $table, $ds);
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
     */
    public function placeTicket($bets, $type, $odd, $stake, $serviceFee, $userId)
    {
        $data['ReservationTicketPart'] = array();

        foreach ($bets as $bet) {
            $bet['BetPart']['bet_part_id'] = $bet['BetPart']['id'];
            $bet['BetPart']['event_id'] = $bet["Bet"]["event_id"];
            $bet['BetPart']["status"] = 0;
            unset($bet['BetPart']['id']);
            $data['ReservationTicketPart'][] = $bet['BetPart'];
        }

        $data['ReservationTicket'] = array(
            'ticket_id'     =>  null,
            'odd'           =>  $odd,
            'amount'        =>  $serviceFee + $stake,
            'type'          =>  $type,
            'service_fee'   =>  Configure::read('Settings.service_fee'),
            'return'        =>  $this->Ticket->getMaxWinning($stake * $odd),
            'date'          =>  gmdate('Y-m-d H:i:s')
        );

        $this->create();

        $this->saveAll($data);

        $ticketsIds = CakeSession::read('ReservationTicketsIds');
        $ticketsIds[] = $this->id;

        CakeSession::write('ReservationTicketsIds', $ticketsIds);
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
        return array(
            0   =>  array(
                'name'          =>  __('View', true),
                'plugin'        =>  $cakeRequest->params['plugin'],
                'controller'    =>  $cakeRequest->params['controller'],
                'action'        =>  'admin_view',
                'class'         =>  'btn btn-mini'
            ),
            1   =>  array(
                'name'          =>  __('Print', true),
                'plugin'        =>  $cakeRequest->params['plugin'],
                'controller'    =>  $cakeRequest->params['controller'],
                'action'        =>  'admin_print',
                'class'         =>  'btn btn-mini'
            )
        );
    }

    /**
     * Returns ticket with ticket part
     *
     * @param $id
     * @return array
     */
    public function getTicket($id)
    {
        $this->contain('ReservationTicketPart');

        return $this->find('first', array(
            'conditions'    =>  array(
                'Ticket.id'     =>  $id
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
                0   =>  'ReservationTicketPart',
                1   =>  'Ticket'
            ),
            'conditions'    =>  array(
                'ReservationTicket.id' => $ticketId
            )
        ));
    }


    /**
     * Admin edit fields
     *
     * @return mixed
     */
    public function getEdit($data)
    {
        return array();
    }

    public function getPagination()
    {
        $parent = parent::getPagination();
        $parent["contain"] = array('Ticket', 'ReservationTicketPart');
        return $parent;
    }

    /**
     * Returns search fields
     *
     * @return array|mixed
     */
    public function getSearch()
    {
        return array();
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
        return array();
    }


    public function setTicketId($reservationTicketId, $ticketId)
    {
        $item2 = $this->getItem($reservationTicketId);

        if (empty($item2)) {
            return false;
        }
        $item2["ReservationTicket"]["ticket_id"] = $ticketId;

        return $this->save($item2);
    }

    public function markPlaced($reservationTicketId)
    {
        $item2 = $this->getItem($reservationTicketId);

        if (empty($item2)) {
            return false;
        }
        $item2["ReservationTicket"]["placed"] = true;

        return $this->save($item2);
    }
}