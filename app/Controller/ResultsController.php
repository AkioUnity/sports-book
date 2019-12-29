<?php
/**
 * Front Results Controller
 *
 * Handles Results Actions
 *
 * @package    Results
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class ResultsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Results';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Result', 'Sport', 'League', 'Event', 'Bet', 'Ticket', 'TicketPart');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
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
        $this->Paginator->settings  =   array($this->Sport->name => array(
            'fields' => array(
                'Sport.id',
                'Sport.name'
            ),
            'limit' => Configure::read('Settings.itemsPerPage')
        ));

        $data    =   $this->Paginator->paginate( $this->Sport->name );
        $this->set('data', $data);
        $this->set('model', 'Sport');
        $this->set('action', 'sport');
        $this->set('tabs', $this->Result->getTabs($this->params->params));
    }

    public function admin_sport($id)
    {
        $this->Paginator->settings  =   array($this->League->name => array(
            'fields' => array(
                'League.id',
                'League.name'
            ),
            'conditions' => array(
                'League.sport_id' => $id
            ),
            'limit' => Configure::read('Settings.itemsPerPage')
        ));

        $data    =   $this->Paginator->paginate( $this->League->name );


        $this->set('data', $data);
        $this->set('model', 'League');
        $this->set('action', 'league');
        $this->set('tabs', $this->Result->getTabs($this->params->params));
    }

    public function admin_league($id)
    {
        $this->Paginator->settings  =   array($this->Sport->Event => array(
            'fields' => array(
                'Event.id',
                'Event.name',
                'Event.result',
                'Event.date'
            ),
            'conditions' => array(
                'Event.league_id' => $id,
                'Event.date <' => gmdate('Y-m-d H:i:s')
            ),
            'limit' => Configure::read('Settings.itemsPerPage')
        ));

        $data  = $this->Paginator->paginate( $this->Event->name );

        $this->set('data', $data);
        $this->set('model', 'Event');
        $this->set('action', 'event');
        $this->set('tabs', $this->Result->getTabs($this->params->params));
    }

    public function admin_event($id = null, $all = 1)
    {
        $model = $this->Event->getItem($id, array('League'));
        $model["Sport"] = current($this->Event->League->Sport->getItem($model["League"]["sport_id"]));

        $this->set('model', $model);
        $bets = array();
        if (empty($this->request->data)) {
            $bets = $this->Event->getBets($id, $all);
        } else {
            if (empty($this->request->data['Event']['result'])) {
                $bets = $this->Event->getBets($id, $all);
                $this->Admin->setMessage(__('Please enter event result', true), 'error');
            } else {
                $this->Event->setResult($this->request->data['Event']['id'], $this->request->data['Event']['result']);
                foreach ($this->request->data['Result'] as $betPartId => $win) {
                    $this->__setWinLoose($betPartId, $win);
                }
                $parentId = $this->Event->getParentId($id);
                $this->Admin->setMessage(__('Results updated', true), 'success');
                $this->redirect(array('action' => 'league', $parentId));
            }
        }

        $this->set('data', $bets);
        $this->set('tabs', $this->Result->getTabs($this->params->params));
    }

    public function admin_pendingSports() {
        $this->admin_allSports(true);

        $this->view = 'admin_allSports';
    }

    public function admin_allAll() {
        
        //parse results first
        if (!empty($this->request->data)) {
            foreach ($this->request->data as $result) {                
                if (!empty($result['Event']['result'])) {                    
                    $this->Event->setResult($result['Event']['id'], $result['Event']['result']);
                    foreach ($result['Result'] as $betPartId => $win) {
                        $this->__setWinLoose($betPartId, $win);
                    }
                }
            }
            $this->Admin->setMessage(__('Results updated'), 'success');
        }
        
        $betPartsIds = $this->TicketPart->getPendingBetParts();
        $betsIds = $this->Bet->BetPart->getBetsIds($betPartsIds);
        $eventsIds = $this->Bet->getEventsIds($betsIds);
        $this->Paginator->settings  =   array($this->Event->name => array(
            'conditions' => array(
                'Event.id' => $eventsIds
            )
        ));

        $events    =   $this->Paginator->paginate( $this->Event->name );
        $betsIds = array_flip($betsIds);
        foreach ($events as $eventKey => $event) {
            $sport = $this->Sport->getItem($event['League']['sport_id']);
            $events[$eventKey]['Sport'] = $sport['Sport'];
            foreach ($event['Bet'] as $betKey => $bet) {
                unset($events[$eventKey]['Bet'][$betKey]);
                if (isset($betsIds[$bet['id']])) {
                    $events[$eventKey]['Bet'][$betKey]['Bet'] = $bet;
                    $events[$eventKey]['Bet'][$betKey]['BetPart'] = $this->Bet->BetPart->getBetParts($bet['id']);
                }
            }
        }

        

        $this->set('data', $events);
        $this->set('tabs', $this->Result->getTabs($this->params->params));
    }

    public function admin_allSports($pending = false)
    {
        $betPartsIds = $this->TicketPart->getPendingBetParts();
        $betsIds = $this->Bet->BetPart->getBetsIds($betPartsIds);
        $eventsIds = $this->Bet->getEventsIds($betsIds);
        $leaguesIds = $this->Event->getLeaguesIds($eventsIds, $pending);
        $sportsIds = $this->League->getSportsIds($leaguesIds);

        $this->Paginator->settings  =   array($this->Sport->name => array(
            'fields' => array(
                'Sport.id',
                'Sport.name'
            ),
            'conditions' => array(
                'Sport.id' => $sportsIds
            )
        ));

        $data  = $this->Paginator->paginate( $this->Sport->name );

        $this->set('data', $data);
        $this->set('model', 'Sport');
        $this->set('action', 'allLeagues');

        if ($pending) {
            $this->set('action', 'pendingLeagues');
        }

        $this->set('tabs', $this->Result->getTabs($this->params->params));
    }

    public function admin_pendingLeagues($sportId) {
        $this->admin_allLeagues($sportId, true);
        $this->view = 'admin_allLeagues';
        //$this->render('admin_allLeagues');
    }

    public function admin_allLeagues($sportId, $pending = false) {
        $betPartsIds = $this->TicketPart->getPendingBetParts();
        $betsIds = $this->Bet->BetPart->getBetsIds($betPartsIds);
        $eventsIds = $this->Bet->getEventsIds($betsIds);
        $leaguesIds = $this->Event->getLeaguesIds($eventsIds, $pending);

        $this->Paginator->settings  =   array($this->League->name => array(
            'fields' => array(
                'League.id',
                'League.name'
            ),
            'conditions' => array(
                'League.id' => $leaguesIds,
                'League.sport_id' => $sportId
            )
        ));

        $data    =   $this->Paginator->paginate( $this->League->name );

        $this->set('data', $data);
        $this->set('model', 'League');
        $this->set('action', 'allEvents');
        if ($pending)
            $this->set('action', 'pendingEvents');
        $this->set('tabs', $this->Result->getTabs($this->params->params));
    }

    public function admin_pendingEvents($leagueId) {
        $this->admin_allEvents($leagueId, true);
        $this->view = 'admin_allEvents';
        //$this->render('admin_allEvents');
    }

    public function admin_allEvents($leagueId, $pending = false) {
        $betPartsIds = $this->TicketPart->getPendingBetParts();
        $betsIds = $this->Bet->BetPart->getBetsIds($betPartsIds);
        $eventsIds = $this->Bet->getEventsIds($betsIds);

        $this->Paginator->settings  =   array($this->Event->name => array(
            'fields' => array(
                'Event.id',
                'Event.name',
                'Event.result',
                'Event.date'
            ),
            'conditions' => array(
                'Event.id' => $eventsIds,
                'Event.league_id' => $leagueId
            )
        ));


        if (!$pending) {
            $this->Paginator->settings[$this->Event->name]['conditions']['Event.date <'] = gmdate('Y-m-d H:i:s');
        }

        $data    =   $this->Paginator->paginate( $this->Event->name );
        $this->set('data', $data);
        $this->set('model', 'Event');
        $this->set('action', 'event');
        $this->set('tabs', $this->Result->getTabs($this->params->params));
    }

    public function admin_cancel($id) {
        $bets = $this->Event->getBets($id);
        foreach ($bets as $bet) {
            $this->__cancelBet($bet);
        }
        $this->Event->setResult($id, 'Cancelled');
        $parentId = $this->Event->getParentId($id);
        $this->Admin->setMessage(__('Results updated', true), 'success');
        $this->redirect(array('action' => 'league', $parentId));
    }

    public function __cancelBet($bet) {
        foreach ($bet['BetPart'] as $betPart) {
            $ticketsParts = $this->Ticket->getTicketsPartsByBetPartId($betPart['id']);
            foreach ($ticketsParts as $ticketPart) {
                $this->TicketPart->setStatus($ticketPart['TicketPart']['id'], Ticket::TICKET_STATUS_CANCELLED);
            }
        }
    }

    public function __setWinLoose($betPartId, $win) {

    	$ticketsParts = $this->Ticket->getTicketsPartsByBetPartId($betPartId);
        
        
        if ($win != 1) {
            $status = Ticket::TICKET_STATUS_LOST;
        } else {
            $status = Ticket::TICKET_STATUS_WON;
            $this->Bet->setPick($betPartId);
        }
        foreach ($ticketsParts as $ticketPart) {
            $this->TicketPart->setStatus($ticketPart['TicketPart']['id'], $status);
        }
        //}
    }

    /**
     * FIXME:DEAD?
     * @param unknown_type $betPartId
     * @param unknown_type $status
     */
    public function __updateTicketPartsStatus($betPartId, $status) {
        App::import('Model', 'TicketPart');
        $TicketPart = new TicketPart();
        $TicketPart->updateAll(array('TicketPart.status' => $status), array('TicketPart.bet_part_id' => $betPartId));
        //get all ticket parts who won/loose
        //$TicketPart->contain('Ticket');
        $tickets = $TicketPart->find('all', array('conditions' => array('TicketPart.bet_part_id' => $betPartId)));
        if (!empty($tickets))
            $this->__updateTickets($tickets, $status);
    }

    public function __updateTickets($tickets, $status) {
        App::import('Model', 'Ticket');
        $Ticket = new Ticket();

        $ticketPartsIds = array();
        foreach ($tickets as $ticket)
            $ticketPartsIds[] = $ticket['Ticket']['id'];

        if ($status == -1) //all lost
            $Ticket->updateAll(array('Ticket.status' => '-1'), array('Ticket.id' => $ticketPartsIds));
        else if ($status == 1) {
            //check  before seting to win and awarding user
            //select all ticket where lost and pending = 0
            foreach ($tickets as $ticket) {
                $count = $Ticket->TicketPart->find('count', array('conditions' => array('TicketPart.ticket_id' => $ticket['Ticket']['id'], 'TicketPart.status <>' => 1)));
                if ($count == 0) {
                    $Ticket->contain();
                    $Ticket->id = $ticket['Ticket']['id'];
                    $Ticket->read();
                    $Ticket->set(array('status' => '1'));

                    $Ticket->save($Ticket->data);

                    //award user for his bright mind
                    $this->__updateUser($ticket);
                }
            }
        }
    }

    public function __updateUser($ticket) {
        App::import('Model', 'User');
        $User = new User();
        $User->contain();
        $User->id = $ticket['Ticket']['user_id'];
        $User->read();
        
        $balance = $User->data['User']['balance'] + $ticket['Ticket']['return'];
        
        $User->set(array('balance' => $balance));
        $User->save($User->data, false);
    }
}