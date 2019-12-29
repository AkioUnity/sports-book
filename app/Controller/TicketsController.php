<?php
/**
 * Front Tickets Controller
 *
 * Handles Tickets Actions
 *
 * @package    Tickets
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::import('Vendor', 'dompdf', array('file' => 'dompdf' . DS . 'autoload.inc.php'));

class TicketsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Tickets';

    /**
     * Additional models
     *
     * @var array
     */
    public $uses = array(
        0   =>  'Ticket',
        1   =>  'ReservationTicket',
        2   =>  'Bet',
        3   =>  'Event',
        4   =>  'BetPart',
        5   =>  'User',
        6   =>  'League',
        7   =>  'Sport'
    );

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        0   =>  'Utils',
        1   =>  'Session',
        2   =>  'RequestHandler',
        3   =>  'Security'
    );

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->Auth->allow(array('getHistory', 'getTicket', 'preview', 'placed'));
        parent::beforeFilter();
    }

    /**
     * Admin Index scaffold functions
     *
     * @param array $conditions -   admin_index scaffold conditions
     * @param null  $model      -   admin_index $model name
     *
     * @return array
     */
    public function admin_index($conditions = array(), $model = null)
    {
        $conditions = array();

        switch (CakeSession::read('Auth.User.group_id')) {
            case Group::ADMINISTRATOR_GROUP:
                break;
            case Group::AGENT_GROUP:
                $users = $this->Ticket->User->find('all', array("conditions" => array(
                    "group_id"      =>  Group::USER_GROUP,
                    "referal_id"    =>  CakeSession::read('Auth.User.id')
                )));
                if (is_array($users) && !empty($users)) {
                    $conditions['Ticket.user_id'] = array_map(function($user){ return $user["User"]["id"]; }, $users);
                } else {
                    $conditions['Ticket.user_id'] = CakeSession::read('Auth.User.id');
                }
                break;
            default:
                $userId = CakeSession::read('Auth.User.id');
                $conditions['Ticket.user_id'] = $userId;
                $this->request->data['Ticket']['user_id'] = $userId;
                break;
        }

        $this->set('chartsData', array(
            0   =>  array(
                'settings'  =>  array(
                    'id'            =>  0,
                    'title'         =>  __('Types chart')
                ),
                'data'      =>  array(
                    0  =>  array(
                        'color' =>  'rgb(237, 194, 64)',
                        'label' =>  __($this->Ticket->assignTicketType(Ticket::TICKET_TYPE_SINGLE)),
                        'count' =>  $this->Ticket->getCount(array_merge(array('Ticket.type' => Ticket::TICKET_TYPE_SINGLE),$conditions))
                    ),
                    1  =>  array(
                        'color' =>  'rgb(77, 153, 211)',
                        'label' =>  __($this->Ticket->assignTicketType(Ticket::TICKET_TYPE_MULTI)),
                        'count' =>  $this->Ticket->getCount(array_merge(array('Ticket.type' => Ticket::TICKET_TYPE_MULTI),$conditions))
                    )
                )
            ),
            1   =>  array(
                'settings'  =>  array(
                    'id'            =>  1,
                    'title'         =>  __('Statuses chart')
                ),
                'data'      =>  array(
                    0   =>  array(
                        'color' =>  'rgb(101, 105, 101)',
                        'label' =>  __($this->Ticket->assignTicketStatus(Ticket::TICKET_STATUS_CANCELLED)),
                        'count' =>  $this->Ticket->getCount(array_merge(array('Ticket.status' => Ticket::TICKET_STATUS_CANCELLED),$conditions))
                    ),

                    1   =>  array(
                        'color' =>  'rgb(203, 75, 75)',
                        'label' =>  __($this->Ticket->assignTicketStatus(Ticket::TICKET_STATUS_LOST)),
                        'count' =>  $this->Ticket->getCount(array_merge(array('Ticket.status' => Ticket::TICKET_STATUS_LOST),$conditions))
                    ),

                    2    =>  array(
                        'color' =>  'rgb(175, 216, 248)',
                        'label' =>  __($this->Ticket->assignTicketStatus(Ticket::TICKET_STATUS_PENDING)),
                        'count' =>  $this->Ticket->getCount(array_merge(array('Ticket.status' => Ticket::TICKET_STATUS_PENDING),$conditions))
                    ),

                    3    =>  array(
                        'color' =>  'rgb(77, 167, 77)',
                        'label' =>  __($this->Ticket->assignTicketStatus(Ticket::TICKET_STATUS_WON)),
                        'count' =>  $this->Ticket->getCount(array_merge(array('Ticket.status' => Ticket::TICKET_STATUS_WON),$conditions))
                    )
                )
            )
        ));


        parent::admin_index($conditions);

        $this->set('mainField', null);
    }

    /**
     * Admin view scaffold functions
     *
     * @param int $id - view id
     *
     * @return void
     */
    public function admin_view($id = -1)
    {
        $ticket = $this->Ticket->getAllTicketInformation($id);

        if (isset($ticket['Ticket']))
        {
            foreach ($ticket['TicketPart'] as &$ticketPart) {
                $options['conditions'] = array('BetPart.id' => $ticketPart['bet_part_id']);

                $this->BetPart->contain('Bet');

                $bet = $this->BetPart->find('first', $options);

                $options['conditions'] = array('BetPart.id' => $bet['Bet']['pick']);

                $correctPick = $this->BetPart->find('first', $options);
                $this->Event->contain();

                $event = $this->Event->getItem($ticketPart['event_id']);

                $ticketPart['BetPart'] = $bet['BetPart'];
                $ticketPart['Bet'] = $bet['Bet'];
                $ticketPart['Event'] = $event['Event'];
                $ticketPart['Bet']['outcome'] = '';

                if (!empty($correctPick)) {
                    $ticketPart['Bet']['outcome'] = $correctPick['BetPart']['name'];
                }

            }
            $this->set('ticket', $ticket);
        }
        else{
            $this->Admin->setMessage(__('Cannot find ticket with id: ', true) . $id, 'error');
        }

        $this->set('tabs', $this->Ticket->getTabs($this->params->params));
    }

    /**
     * Cancels ticket
     *
     * Returns funds to user
     *
     * @param int $ticketId - Ticket Id
     *
     * @return void
     */
    public function admin_cancel($ticketId)
    {
        $ticket = $this->Ticket->getItem($ticketId);

        if (empty($ticket)) {
            $this->redirect($this->referer());
            exit;
        }

        $this->Ticket->cancel($ticketId);

        $this->redirect($this->referer());
    }

    /**
     * Marks ticket as paid for operator
     *
     * @param null $ticketId - ticketId
     *
     * @return void
     */
    public function admin_markAsPaid($ticketId = null)
    {
        if ($ticketId == null || !is_numeric($ticketId)) {
            $this->redirect(array('controller' => 'tickets', 'action' => 'index'), 302, true);
        }

        $userId = CakeSession::read('Auth.User.id');

        $this->Ticket->contain('TicketPart');

        $options['conditions'] = array('Ticket.id' => $ticketId, 'Ticket.user_id' => $userId);

        $ticket = $this->Ticket->find('first', $options);

        if(empty($ticket)) {
            $this->redirect(array('controller' => 'tickets', 'action' => 'index'), 302, true);
        }

        $ticket['Ticket']['paid'] = 1;

        $this->Ticket->save($ticket);

        $this->redirect(array('controller' => 'tickets', 'action' => 'index'), 302, true);
    }


    public function admin_getBets()
    {
        $this->getBets();
    }


    public function admin_updateEvents($ticketId = null)
    {
        $Events = array();
        $Ticket = $this->Ticket->getAllTicketInformation($ticketId);

        if (empty($Ticket)) {
            $this->Admin->setMessage(__("Cannot find ticket with id: %d ", (int) $ticketId), 'error');
            $this->redirect('index', null, true);
        }

        foreach ($Ticket['TicketPart'] AS $ticketPart) {
            $Bet = $this->BetPart->getItem($ticketPart['bet_part_id'], array('Bet'));

            $Event = $this->Event->getItem($Bet['Bet']['event_id']);

            $Events[] = $Event['Event']['import_id'];
        }

        Queue::add('Feeds.FeedApp updateEvents ' . implode(' ', $Events), 'shell', $options = array());

        $this->Admin->setMessage(__("Ticket with id: %d events information update are scheduled. Typically one event updates in one minute.", (int) $ticketId), 'success');

        $this->redirect(array('admin' => true, 'plugin' => null, 'controller' => 'tickets', 'action' => 'view', $ticketId), null, true);
    }
    /**
     * Ajax function to get ticket
     *
     * @throws Exception
     */
    public function getTicket()
    {
        if (!$this->request->is('ajax')) {
            $this->Security->blackHole($this, 'You are not authorized to process this request!');
        }

        if(!isset($this->request->query['TicketId'])) {
            throw new Exception('Undefined ticket id');
        }

        $this->Ticket->contain();

        $data = $this->Ticket->find('first', array(
            'fields'        =>  array(
                0   =>  'id',
                1   =>  'status',
                2   =>  'paid'
            ),
            'conditions'    =>  array(
                'Ticket.id'         =>  $this->request->query['TicketId'],
                'Ticket.user_id'    =>  CakeSession::read('Auth.User.id')
            )
        ));

        if(!isset($data['Ticket']) || empty($data['Ticket'])) {
            $data['Ticket']['response'] = 404;
            $data['Ticket']['message']  = __('Ticket is not found');
            $data['Ticket']['status']   = __('Unknown ticket status');
            $data['Ticket']['paid']     = __('Unknown paid status');
        }else{
            $data['Ticket']['response'] = 200;
            $data['Ticket']['message']  = __('Ok');
            $data['Ticket']['status']   = $this->Ticket->getTicketStatus($data['Ticket']['status']);
            $data['Ticket']['paid']     = $this->Ticket->assignTicketPaid($data['Ticket']['paid']);
        }

        $this->set(compact('data'));
        $this->set('_serialize', array('data'));
    }

    /**
     * Ajax function to pay for ticket
     */
    public function payForTicket()
    {
        if (!$this->request->is('ajax')) {
            if (!isset($this->request->params['pass'][0]) || !is_numeric($this->request->params['pass'][0])) {
                $this->Security->blackHole($this, 'You are not authorized to process this request!');
            }
            $TicketId = $this->request->params['pass'][0];
        } else {
            if(!isset($this->request->query['TicketId'])) {
                throw new Exception('Undefined ticket id', 500);
            }
            $TicketId = $this->request->query['TicketId'];
        }

        if(CakeSession::read('Auth.User.Group.id') != Group::OPERATOR_GROUP) {
            throw new Exception('You have insufficient rights to access this page', 500);
        }

        $Ticket = $this->Ticket->find('first', array(
            'fields'        =>  array(
                0   =>  'id',
                1   =>  'status',
                2   =>  'paid',
                3   =>  'return'
            ),
            'conditions'    =>  array(
                'Ticket.id'         =>  $TicketId,
                'Ticket.user_id'    =>  CakeSession::read('Auth.User.id'),
                'Ticket.paid !='    =>  Ticket::PAID
            )
        ));

        $data = array('response' => 'ok', 'status' => $this->Ticket->assignTicketPaid(false));

        $data['response'] = 'error';
        $data['message']  = __('Ticket not found or is already marked as paid');

        if(!empty($Ticket)) {
            if($Ticket['Ticket']['status'] == Ticket::TICKET_STATUS_WON) {
                if($Ticket['Ticket']['return'] <= CakeSession::read('Auth.User.balance')) {
                    $this->Ticket->setAsPaid($Ticket['Ticket']['id']);
                    $balance = $this->User->addFunds(CakeSession::read('Auth.User.id'), -($Ticket['Ticket']['return']));
                    $data['response']   = 'ok';
                    $data['status']     = $this->Ticket->assignTicketPaid(Ticket::PAID);
                    $data['balance']    = $balance;
                }else{
                    $data['response'] = 'error';
                    $data['message']  = __('Insufficient credits');
                }
            }
            else{
                $data['response'] = 'error';
                $data['message']  = __('Ticket is still pending or lost');
            }
        }

        if(!$this->request->is('ajax')) {
            switch($data['response']) {
                case 'ok':
                    $this->Admin->setMessage($data['message'], 'success');
                    break;
                case 'error':
                    $this->Admin->setMessage($data['message'], 'error');
                    break;
            }

            $this->redirect(array('plugin' => null, 'admin' => true, 'controller' => 'tickets', 'action' => 'index'), null, true);
        }

        $this->set(compact('data'));
        $this->set('_serialize', array('data'));
    }

    /**
     * Print ticket
     *
     * @param $id
     */
    public function printTicket($id)
    {
        $this->Ticket->contain('TicketPart');

        $Ticket = $this->Ticket->find('first', array(
            'conditions'    =>  array('Ticket.id' => $id)
        ));

        if(empty($Ticket)) {
            $this->App->setMessage(__('Ticket does not exist. Ticket ID: %d', $id), 'error');
            $this->redirect($this->referer(), 302, true);
        }

        if (CakeSession::read('Auth.User.group_id') != Group::ADMINISTRATOR_GROUP) {
            if (Configure::read('Settings.printing') != 1) {
                $this->redirect($this->referer());
            } else {                
                if ($Ticket['Ticket']['printed'] == 1) {
                    $this->App->setMessage(__('Ticket already printed. Ticket ID: %d', $id), 'error');
                    $this->redirect($this->referer());
                }
                if ($this->Auth->user('id') != $Ticket['Ticket']['user_id']) {
                    $this->App->setMessage(__('Cannot find ticket. Ticket ID: %d', $id), 'error');
                    $this->redirect($this->referer());
                }
            }
        }

        foreach ($Ticket['TicketPart'] AS &$ticketPart) {
            $this->Bet->BetPart->contain('Bet');
            $bet = $this->Bet->BetPart->find('first', array(
                'conditions'    =>  array('BetPart.id' => $ticketPart['bet_part_id'])
            ));
            $event = $this->Event->getItem($bet['Bet']['event_id']);
            $ticketPart['BetPart'] = $bet['BetPart'];
            $ticketPart['Bet'] = $bet['Bet'];
            $ticketPart['Event'] = $event['Event'];
        }

        usort($Ticket['TicketPart'], function($a1, $a2) {
            $v1 = strtotime($a1['Event']['date']);
            $v2 = strtotime($a2['Event']['date']);
            return $v2 - $v1;
        });

        $User = $this->User->getItem($Ticket["Ticket"]["user_id"]);

        $Ticket["User"] = $User["User"];
        $Ticket["User"]["isStaff"] = false;

        switch($Ticket["User"]["group_id"]) {
            case Group::ADMINISTRATOR_GROUP:
            case Group::AGENT_GROUP:
            case Group::BRANCH_GROUP:
            case Group::CASHIER_GROUP:
            case Group::OPERATOR_GROUP:
            $Ticket["User"]["isStaff"] = true;
                break;
        }

        $this->autoRender = false;

        $view = new View($this);
        $view->set('ticket', $Ticket);
        $view->set('currency', Configure::read('Settings.currency'));
        $view->layout = 'printing';

        $pdfFile = TMP . 'tickets' . DS . 'pdf' . DS . 'ticket-' . $id . '.pdf';

        $CacheFolder = new Folder();

        if(!$CacheFolder->cd(TMP . 'tickets' . DS . 'pdf')) {
            new Folder(TMP . 'tickets' . DS . 'pdf', true, 0777);
        }

        if(!$CacheFolder->cd(TMP . 'tickets' . DS . 'html')) {
            new Folder(TMP . 'tickets' . DS . 'html', true, 0777);
        }

        if (isset($this->request->params["pass"][1]) && $this->request->params["pass"][1] == "html") {
            echo $view->render();
            exit;
        }

        $pdf = new File($pdfFile);

        if(!$pdf->exists()) {

            $html = $view->render();

            $GLOBALS['bodyHeight'] = 0;

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->setPaper(array(0, 0, 240.00, 900.00));
            $dompdf->setCallbacks(
                array(
                    'myCallbacks' => array(
                        'event' => 'end_frame', 'f' => function ($infos) {
                            $frame = $infos["frame"];
                            if (strtolower($frame->get_node()->nodeName) === "body") {
                                $padding_box = $frame->get_padding_box();
                                $GLOBALS['bodyHeight'] += $padding_box['h'];
                            }
                        }
                    )
                )
            );
            $dompdf->loadHtml($html);
            $dompdf->render();
            unset($dompdf);

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->setPaper(array(0, 0, 240.00, $GLOBALS['bodyHeight']+10));
            $dompdf->loadHtml($html);
            $dompdf->render();
            $pdf->write($dompdf->output());
        }

        header('Content-type: application/pdf');

        @readfile($pdfFile);

        exit;
    }

    /**
     * Adds bet
     *
     * @param $betPartId
     */
    public function addBet($betPartId)
    {
        if (!$this->request->is('ajax')) {
            $this->Security->blackHole($this, 'You are not authorized to process this request!');
        }

        $addBetStatus = $this->Ticket->addBetToSession($betPartId);

        // Something gone wrong..
        if($addBetStatus["status"] == false) {
            $this->App->setMessage($addBetStatus["msg"], 'error');
        }

        $isAjax = false;

        if(isset($this->request->params['pass'][1])) {
            if($this->request->params['pass'][1] == 'ajax') {
                $isAjax = true;
            }
        }

        $this->getBets($isAjax);
    }

    /**
     * Ajax function
     * Renders bet slip block
     */
    public function getBets($isAjax = null)
    {
        if (!$this->request->is('ajax')) {
            $this->Security->blackHole($this, 'You are not authorized to process this request!');
        }

        if (isset($_GET["action"]) && $_GET["action"] == "flush") {
            $this->Ticket->flushBetSession();
        }

        $bets = $this->Ticket->getBetsFromSession();

        $betsCount = count($bets);

        $type = $this->Ticket->getTicketType(false);

        $totalStake = str_replace(',', '.', $this->Ticket->getStake());

        $totalOdds = $this->Ticket->getOdds();
        $totalWinning = $this->Ticket->getWinning($type, $totalOdds, $totalStake);

        if(isset($this->request->params['pass'][0])) {
            if($this->request->params['pass'][0] == 'ajax') {
                $this->set('ajaxBetting', true);
            }
        }

        if($isAjax == true) {
            $this->set('ajaxBetting', true);
        }

        $this->set(compact('type', 'bets', 'betsCount', 'totalOdds', 'totalStake', 'totalWinning'));

        $this->set('tickets', array(
            0   =>  array(
                'Ticket'    =>  array(
                    'odd'       =>  $totalOdds,
                    'stake'     =>  $totalStake,
                    'winning'   =>  $this->Ticket->getMaxWinning($totalWinning),
                ),
                'Bets'      =>  $bets
            )
        ));

        $this->layout = 'ajax';

        $this->render('betslip');
    }

    /**
     * Tickets Ajax History Block
     */
    public function getHistory()
    {
        if (!$this->request->is('ajax')) {
            $this->Security->blackHole($this, 'You are not authorized to process this request!');
        }

        $lastTickets = $this->Ticket->find('all', array(
            'conditions'    =>  array(
                'Ticket.user_id'              =>  $this->Session->read('Auth.User.id'),
            ),
            'order' =>  array('Ticket.date DESC'),
            'limit' =>  7
        ));

        foreach($lastTickets AS $index => $lastTicket) {
            $lastTickets[$index]['Ticket']['isCancellable'] = $this->Ticket->isCancellable($lastTicket['Ticket']['id']);
        }

        $this->layout = 'ajax';

        $this->set('lastTickets', $lastTickets);
    }

    public function removeBet($betPartId)
    {
        if (CakeSession::check('Ticket.bets')) {
            $bets = CakeSession::read('Ticket.bets');
            foreach ($bets as $key => $bet) {
                if ($bet['BetPart']['id'] == $betPartId) {
                    unset($bets[$key]);
                    break;
                }
            }
            CakeSession::write('Ticket.bets', $bets);
        }

        $isAjax = false;

        if(isset($this->request->params['pass'][1])) {
            if($this->request->params['pass'][1] == 'ajax') {
                $isAjax = true;
            }
        }

        $this->getBets($isAjax);
    }

    /**
     *  Clears ticket (Resets bet slip)
     */
    public function delete() {
        CakeSession::write('Ticket.bets', array());
        $this->redirect(Router::url('/', true), null, true);
    }

    /**
     * Front set stake function
     */
    public function setStake()
    {
        if (!$this->request->is('ajax') || !isset($this->request->query['stake'])) {
            $this->Security->blackHole($this, 'You are not authorized to process this request!');
        }

        $stake = str_replace(',', '.', $this->request->query['stake']);

        $checkStakeLimits = $this->Ticket->checkStakeLimits($stake);

        if ($checkStakeLimits["status"] != "ok") {
            $data = $checkStakeLimits;
        }
        else{
            $this->Ticket->setStake($stake);
            $data = array(
                'status'            =>  'ok',
                'stake'             =>  sprintf('%s', number_format((float)$this->Ticket->getStake(), intval(Configure::read('Settings.balance_decimal_places')), '.', ' ')),
                'stake_total'       =>  sprintf('%s', number_format((float)$this->Ticket->getStake() + $this->App->calculatePercentageAfter($this->Ticket->getStake(), Configure::read('Settings.service_fee')), intval(2), '.', ' ')),
                'service_fee'       =>  sprintf('%s', number_format((float)$this->App->calculatePercentageAfter($this->request->query['stake'], Configure::read('Settings.service_fee')), intval(2), '.', ' ')),
                'possible_wining'   =>  sprintf('%s', number_format((float)$this->Ticket->getWinning($this->Ticket->getTicketType(), $this->Ticket->getOdds(), $this->Ticket->getStake()), intval(2), '.', ' '))
            );
        }

        $this->set(compact('data'));
        $this->set('_serialize', array('data'));
    }

    /**
     * Place ticket
     */
    public function place()
    {
        $addTicketCheck = $this->Ticket->addTicketCheck();

        if (isset($addTicketCheck["data"])) {
            $this->App->setMessage($addTicketCheck["data"], 'BetSlip_Error_Events_Already_Started');
        }

        $jsonRequest    = $this->response->type() === 'application/json';

        if(!$addTicketCheck['status'] && $addTicketCheck['msg'] != null)
        {
            if($jsonRequest !== true) {
                $this->App->setMessage($addTicketCheck['msg'], 'error');

                $this->redirect($this->referer(), 302, true);
            }

            $data = (array( 'response'  =>  'error', 'message' =>  $addTicketCheck['msg'] ));

            $this->set(compact('data'));
            $this->set('_serialize', array('data'));
        }else{
            $userId = CakeSession::read('Auth.User.id');

            $TicketBets     = $this->Ticket->getBetsFromSession();

            $TicketStake    = $this->Ticket->getStake();

            $TicketServiceFee = $this->App->calculatePercentageAfter($this->Ticket->getStake(), Configure::read('Settings.service_fee'));

            $TicketType     = $this->Ticket->getTicketType();

            if (Configure::read('Settings.reservation_ticket_mode') && !CakeSession::check('Auth.User.id')) {
                if ($TicketType == Ticket::TICKET_TYPE_SINGLE) {
                    foreach ($TicketBets as $bet) {
                        $this->ReservationTicket->placeTicket(array($bet), $TicketType, $bet['BetPart']['odd'], $TicketStake, $TicketServiceFee, $userId);
                    }
                }else{
                    $this->ReservationTicket->placeTicket($TicketBets, $TicketType, $this->Ticket->getOdds(), $TicketStake, $TicketServiceFee, $userId);
                }
            } else {
                if ($TicketType == Ticket::TICKET_TYPE_SINGLE) {
                    foreach ($TicketBets as $bet) {
                        $this->Ticket->placeTicket(array($bet), $TicketType, $bet['BetPart']['odd'], $TicketStake, $TicketServiceFee, $userId);
                    }
                }else{
                    $this->Ticket->placeTicket($TicketBets, $TicketType, $this->Ticket->getOdds(), $TicketStake, $TicketServiceFee, $userId);
                }
            }

            $this->Ticket->flushBetSession();


            if($jsonRequest !== true) {
                if (Configure::read('Settings.reservation_ticket_mode') && !CakeSession::check('Auth.User.id')) {
                    $this->redirect(array('controller' => 'ReservationTickets', 'action' => 'index'), 302, true);
                } else {
                    $this->redirect(array('controller' => 'tickets', 'action' => 'index'), 302, true);
                }
            }

            $data = (array( 'response'  =>  'ok', 'message' =>  null ));

            $this->set(compact('data'));
            $this->set('_serialize', array('data'));
        }
    }

    public function placed()
    {
        $this->layout = 'user-panel';

        if (!isset($this->params['pass'][0])) {
            $this->redirect(array('controller' => 'tickets', 'action' => 'index'));
        }

        $ticket = $this->Ticket->find('first', array(
            'contain'       =>  array(
                0   =>  'TicketPart'
            ),
            'conditions'    =>  array(
                'Ticket.id' => $this->params['pass'][0], 'Ticket.user_id' => $this->Auth->user('id')
            )
        ));

        if (isset($ticket['Ticket'])) {
            $this->set('ticket', $ticket);
        }
    }

    /**
     * Show tickets to user
     *
     * return @void
     */
    public function index()
    {
        $this->layout = 'user-panel';

        $userId = CakeSession::read('Auth.User.id');

        $this->Ticket->contain(array('TicketPart', 'User'));

        $this->Ticket->locale       =   Configure::read('Config.language');
        $this->Paginator->settings  =   array($this->Ticket->name => array(
            "conditions"    =>  array(
                'Ticket.user_id'    => $userId,
                'Ticket.status'     => Ticket::TICKET_STATUS_PENDING
            ),
            "order" =>  array(
                'Ticket.date' => 'DESC'
            ),
            "limit" =>  20
        ));

        $tickets    =   $this->Paginator->paginate( $this->Ticket->name );

        $this->set(compact('tickets'));
    }

    /**
     * Show tickets history to user
     *
     * return @void
     */
    public function history()
    {
        $this->layout = 'user-panel';

        $userId = CakeSession::read('Auth.User.id');

        $this->Ticket->contain(array('TicketPart', 'User'));

        $this->Paginator->settings  =   array($this->Ticket->name => array(
            "conditions"    =>  array(
                'Ticket.user_id'    => $userId,
                'Ticket.status !='  => 0
            ),
            "order" =>  array(
                'Ticket.date' => 'DESC'
            ),
            "limit" =>  20
        ));

        $tickets    =   $this->Paginator->paginate( $this->Ticket->name );

        $this->set(compact('tickets'));
    }

    /**
     * Show ticket details to user
     *
     * return @void
     */
    public function view()
    {
        $this->layout = "user-tools";


        if (!isset($this->params['named']['ticketId'])) {
            $this->redirect(array('controller' => 'tickets', 'action' => 'index'));
        }

        $ticketId = $this->params['named']['ticketId'];

        $this->Ticket->contain('TicketPart');

        $options['conditions'] = array();

        $ticket = $this->Ticket->find('first', array(
            'contain'       =>  array(
                0   =>  'TicketPart'
            ),
            'conditions'    =>  array(
                'Ticket.id' => $ticketId, 'Ticket.user_id' => $this->Auth->user('id')
            )
        ));

        if (isset($ticket['Ticket'])) {
            foreach ($ticket['TicketPart'] as $index => $ticketPart) {

                $Bet = $this->BetPart->find('first', array(
                    'contain'       =>  array('Bet'),
                    'conditions'    =>  array('BetPart.id' => $ticketPart['bet_part_id'])
                ));

                $correctPick = $this->BetPart->find('first', array(
                    'conditions'    =>  array('BetPart.id' => $Bet['Bet']['pick'])
                ));

                $Event = $this->Event->getItem($ticketPart['event_id']);

                $League = $this->League->find('first', array(
                    'contain'       =>  array(),
                    'conditions'    =>  array('League.id' => $Event['Event']['league_id'])
                ));


                $this->Sport->bindTranslation(array('name'));
                $Sport = $this->Sport->getItem($League['League']["sport_id"]);

                $League['Sport'] = isset($Sport["Sport"]) ? $Sport["Sport"] : array();

                $ticket['TicketPart'][$index]['League'] = $League['League'];
                $ticket['TicketPart'][$index]['Sport'] = $League['Sport'];

                $ticket['TicketPart'][$index]['BetPart'] = $Bet['BetPart'];
                $ticket['TicketPart'][$index]['Bet'] = $Bet['Bet'];
                $ticket['TicketPart'][$index]['Event'] = $Event['Event'];
                $ticket['TicketPart'][$index]['Bet']['outcome'] = '';

                if (!empty($correctPick)) {
                    $ticketPart['Bet']['outcome'] = $correctPick['BetPart']['name'];
                }
            }

            $this->set('ticket', $ticket);
        }
    }

    /**
     * Preview ticket placement
     *
     * Returns ticket with bets
     */
    public function preview()
    {
        $this->layout = "user-tools";

        if (!CakeSession::check('Ticket.bets')) {
            $this->redirect('/', null, true);
        }

        if (!CakeSession::check('Auth.User.id')) {
            $this->redirect('/', null, true);
        }

        if(CakeSession::read('Auth.User.allow_betslip') != 1) {
            $this->redirect('/', null, true);
        }

        if (count($this->Ticket->getBetsFromSession()) < Configure::read('Settings.minBetsCount')) {
            $this->redirect('/', null, true);
        }

        $checkStakeLimits = $this->Ticket->checkStakeLimits(CakeSession::read('Ticket.stake'));

        if ($checkStakeLimits['status'] != "ok") {
            $this->redirect('/', null, true);
        }

        $checkDates  = $this->Ticket->checkDates();

        if (!empty($checkDates)) {
            $this->redirect('/', null, true);
        }

        $this->Ticket->renewBetsFromSession();

        $bets = $this->Ticket->getBetsFromSession();

        $betsCount = count($bets);

        $type = $this->Ticket->getTicketType();

        $totalStake = $this->Ticket->getStake();

        $totalOdds = $this->Ticket->getOdds();
        $totalWinning = $this->Ticket->getWinning($type, $totalOdds, $totalStake);

        $this->set(compact('type', 'bets', 'betsCount', 'totalOdds', 'totalStake', 'totalWinning'));

        $this->set('tickets', array(
            0   =>  array(
                'Ticket'    =>  array(
                    'odd'       =>  $totalOdds,
                    'stake'     =>  $totalStake,
                    'winning'   =>  $this->Ticket->getMaxWinning($totalWinning),
                ),
                'Bets'      =>  $bets
            )
        ));

        $this->view = 'preview';
    }
}