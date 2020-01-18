<?php
/**
 * Handles Dashboard
 *
 * Handles Dashboard Actions
 *
 * @package    Dashboard
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class DashboardController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Dashboard';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0   =>  'Dashboard',
        1   =>  'User',
        2   =>  'Ticket',
        3   =>  'Deposit',
        4   =>  'Withdraw',
        5   =>  'Event'
    );

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('usersCount', $this->User->getCount());
        $this->set('ticketsCount', $this->Ticket->getCount());
        $this->set('depositsCount', $this->Deposit->getCount());
        $this->set('withdrawsCount', $this->Withdraw->getCount());

        $this->User->Behaviors->attach('Containable');

        $this->set('activeUsers', $this->User->getCount(array('ticket_count <> 0')));
//        echo (strtolower(CakeSession::read('Auth.User.Group.name')));
//        echo ("before filter");
//        $this->redirect(
//            array(
//                'controller' => 'dashboard',
//                'action' => strtolower(CakeSession::read('Auth.User.Group.name'))),
//            null,
//            true
//        );
//        Die (strtolower(CakeSession::read('Auth.User.Group.name')));
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

//        Die (strtolower(CakeSession::read('Auth.User.Group.name')));
        //prevent redirect loop in case group don\'t have access to dashboard \
        if (!$this->Session->check('Auth.User.id')) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'), null, true);
//            echo ("4");
        }
//        echo ("5");
        // Redirect to user type dashboard
        $this->redirect(
            array(
                'controller' => 'dashboard',
                'action' => strtolower(CakeSession::read('Auth.User.Group.name'))),
            null,
            true
        );

    }

    /**
     * Administrator Dashboard init
     */
    public function admin_administrator()
    {
        if(!$this->Dashboard->isDashboardGroupValid('administrator')) {
            $this->redirect(
                array(
                    'controller' => 'dashboard',
                    'action' => strtolower(CakeSession::read('Auth.User.Group.name'))),
                null,
                true
            );
        }
    }

    /**
     * Operator Dashboard init
     */
    public function admin_operator()
    {
        if(!$this->Dashboard->isDashboardGroupValid('operator')) {
            $this->redirect(
                array(
                    'controller' => 'dashboard',
                    'action' => strtolower(CakeSession::read('Auth.User.Group.name'))),
                null,
                true
            );
        }

        $ticketModel            =   new Ticket();
        $registrationDate       =   $this->Auth->user('registration_date');
        $totalTicketsPlaced     =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $this->Auth->user('id'))));
        $totalTicketsWon        =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $this->Auth->user('id'), 'Ticket.status' => Ticket::TICKET_STATUS_WON)));
        $totalTicketsLost       =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $this->Auth->user('id'), 'Ticket.status' => Ticket::TICKET_STATUS_LOST)));
        $totalTicketsPending    =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $this->Auth->user('id'), 'Ticket.status' => Ticket::TICKET_STATUS_PENDING)));
        $totalTicketsCancelled  =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $this->Auth->user('id'), 'Ticket.status' => Ticket::TICKET_STATUS_CANCELLED)));

        $depositModel           =   new Deposit();
        $withdrawModel          =   new Withdraw();
        $userBalance            =   $this->Auth->user('balance');
        $userLastDeposit        =   $depositModel->find('first', array('conditions' => array('Deposit.user_id' => $this->Auth->user('id')), 'order'=>'Deposit.id DESC'));
        $userAverageDeposit     =   $depositModel->find('first', array('conditions' => array('Deposit.user_id' => $this->Auth->user('id')), 'fields'=> array("AVG(Deposit.amount) AS AverageDeposit")));
        $userLastWithdraw       =   $withdrawModel->find('first', array('conditions' => array('Withdraw.user_id' => $this->Auth->user('id')), 'order'=>'Withdraw.id DESC'));
        $userAverageWithdraw    =   $withdrawModel->find('first', array('conditions' => array('Withdraw.user_id' => $this->Auth->user('id')), 'fields'=> array("AVG(Withdraw.amount) AS AverageWithdraw")));

        $this->Paginator->settings      =   array('Ticket' => array(
            'contain'   =>  array('User'),
            'order'=>'Ticket.id DESC',
            'conditions' => array('Ticket.user_id' => $this->Auth->user('id')),
            'limit' => 10
        ));

        $this->set('registration_date', $registrationDate);
        $this->set('total_tickets_placed', $totalTicketsPlaced);
        $this->set('total_tickets_won', $totalTicketsWon);
        $this->set('total_tickets_lost', $totalTicketsLost);
        $this->set('total_tickets_pending', $totalTicketsPending);
        $this->set('total_tickets_cancelled', $totalTicketsCancelled);
        $this->set('user_balance', $userBalance);
        $this->set('user_last_deposit', $userLastDeposit);
        $this->set('user_average_deposit', $userAverageDeposit);
        $this->set('user_last_withdraw', $userLastWithdraw);
        $this->set('user_average_withdraw', $userAverageWithdraw);


        $this->set('data',  $this->Paginator->paginate($ticketModel));
    }

    /**
     * Cashier Dashboard init
     */
    public function admin_cashier()
    {
        if(!$this->Dashboard->isDashboardGroupValid('cashier')) {
            $this->redirect(
                array(
                    'controller' => 'dashboard',
                    'action' => strtolower(CakeSession::read('Auth.User.Group.name'))),
                null,
                true
            );
        }
    }

    public function admin_agent() {
//        Die (strtolower(CakeSession::read('Auth.User.Group.name')));
        $this->set('user', $this->Auth->user());
        $this->set('referral_url', Router::url('/?r=' . $this->Auth->user('id'), true));
    }

    public function admin_branch()
    {
        $this->set('referral_url', Router::url('/?r=' . $this->Auth->user('id'), true));
    }

    public function admin_subadmin()
    {
        Die (strtolower(CakeSession::read('Auth.User.Group.name')));
    }

    public function admin_tickets()
    {
        $this->Ticket->contain(array('TicketPart', 'User'));

        $this->Ticket->locale       =   Configure::read('Config.language');
        $this->Paginator->settings  =   array($this->Ticket->name => array(
            "fields"        =>  array(
                "Ticket.*",
                "User.id",
            ),
            "conditions"    =>  array(
//                'Ticket.status'     => Ticket::TICKET_STATUS_PENDING
            ),
            "order" =>  array(
                'Ticket.date' => 'DESC'
            ),
            "limit" =>  20
        ));

        $tickets    =   $this->Paginator->paginate( $this->Ticket->name );

        foreach ($tickets AS $ti => $ticket) {
            foreach ($ticket['TicketPart'] as $pi => $ticketPart) {
                $tickets[$ti]["TicketPart"][$pi]["Event"] = current($this->Event->find('first', array(
                    'recursive'     =>  -1,
                    'contain'       =>  array(),
                    'fields'        =>  array(
                        'Event.id',
                        'Event.name',
                        'Event.result',
                    ),
                    'conditions'    =>  array(
                       'Event.id' =>  $ticketPart['event_id']
                    )
                )));
            }
        }

        $this->set(compact('tickets'));
        $this->set('_serialize', array('tickets'));

        if (!empty($this->request->params["pass"]) && $this->request->params["pass"][0] == "json") {
            $view = new View($this);
            $content = $view->element('Dashboard/tickets', array('tickets' => $tickets));
            echo $content;
            exit;
        }
    }
}