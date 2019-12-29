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

class ReservationTicketsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'ReservationTickets';

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
        $this->Auth->allow(array('index', 'view', 'printTicket'));
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
     * Show tickets to user
     *
     * return @void
     */
    public function index()
    {
        $this->layout = 'user-panel';

        $tickets = CakeSession::read('ReservationTicketsIds');

        if (empty($tickets)) {
            $this->redirect('/', null, true);
        }

        $this->ReservationTicket->contain(array('ReservationTicketPart', 'Ticket'));
        $this->ReservationTicket->locale       =   Configure::read('Config.language');
        $this->Paginator->settings  =   array($this->ReservationTicket->name => array(
            "conditions"    =>  array(
                'ReservationTicket.id'    => $tickets
            ),
            "order" =>  array(
                'ReservationTicket.date' => 'DESC'
            ),
            "limit" =>  20
        ));

        $tickets    =   $this->Paginator->paginate( $this->ReservationTicket->name );

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

        $tickets = CakeSession::read('ReservationTicketsIds');

        if (!isset($this->params['named']['ticketId']) || !in_array($this->params['named']['ticketId'], (array) $tickets)) {
            $this->redirect('/', null, true);
        }

        $this->set('ticket', $this->find($this->params['named']['ticketId']));
    }


    public function search()
    {
        if (!isset($this->params->query["id"])) {
            $this->redirect('/', null, true);
        }

        $ticket = $this->find($this->params->query["id"]);

        if (!empty($ticket) && !$ticket["ReservationTicket"]["placed"] && isset($this->params->query["placeTicket"]))
        {
            $userId = CakeSession::read('Auth.User.id');
            $TicketType = $ticket["ReservationTicket"]["type"];
            $TicketOdds = $ticket["ReservationTicket"]['odd'];
            $TicketStake = $ticket["ReservationTicket"]['amount'] - $ticket["ReservationTicket"]['service_fee'];
            $TicketServiceFee = $ticket["ReservationTicket"]['service_fee'];
            $reservationTicketId = $ticket["ReservationTicket"]["id"];

            foreach ($ticket["ReservationTicketPart"] AS $part)
            {
                $event = $this->Event->getItem($part["Bet"]["event_id"]);

                if (empty($event)) {
                    $this->App->setMessage(__('Event with ID: %d no longer available.', $part["Bet"]["event_id"]), 'error');
                    $this->redirect($this->referer(), 302, true);
                    break;
                }

                if (strtotime($event["Event"]["date"]) < strtotime(gmdate("Y-m-d H:i:s", time())) || !$event["Event"]["active"]) {
                    $this->App->setMessage(__('Event %s is already started.', $event["Event"]["name"], $part["Bet"]["event_id"]), 'error');
                    $this->redirect($this->referer(), 302, true);
                    break;
                }

                $event = $this->Event->find('first', array(
                        'contain'       =>  array(),
                        'conditions'            =>  array(
                            'Event.id'          =>  $part["Bet"]["event_id"],
                            'Event.date > ?'    =>  array(gmdate('Y-m-d H:i:s'))
                        )
                    )
                );

                if (empty($event)) {
                    $this->App->setMessage(__('Event with ID: %d already started.', $part["Bet"]["event_id"]), 'error');
                    $this->redirect($this->referer(), 302, true);
                    break;
                }
            }

            $ticket["ReservationTicketPart"] = array_map(function($part){
                return array(
                    "Bet"       =>  array("event_id"=>$part["event_id"]),
                    "BetPart"   =>  array(
                        "id"    =>  $part["bet_part_id"],
                        "odd"   =>  $part["odd"],
                        "status"    =>  $part["status"]
                    )
                );
            }, $ticket["ReservationTicketPart"]);

            $ticketId = $this->Ticket->placeTicket($ticket["ReservationTicketPart"], $TicketType, $TicketOdds, $TicketStake, $TicketServiceFee, $userId, $reservationTicketId);

            $this->ReservationTicket->setTicketId($reservationTicketId, $ticketId);
            $this->ReservationTicket->markPlaced($reservationTicketId);

            $this->redirect(array('controller' => 'tickets', 'action' => 'index'), 302, true);

            $ticket = $this->find($this->params->query["id"]);
        }

        $this->set('ticket', $ticket);
    }


    private function find($ticketId)
    {
        $this->ReservationTicket->contain('ReservationTicketPart');

        $options['conditions'] = array();

        $ticket = $this->ReservationTicket->find('first', array(
            'contain'       =>  array(
                0   =>  'ReservationTicketPart'
            ),
            'conditions'    =>  array(
                'ReservationTicket.id' => $ticketId
            )
        ));

        if (isset($ticket['ReservationTicket'])) {
            foreach ($ticket['ReservationTicketPart'] as $index => $ticketPart) {

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

                $ticket['ReservationTicketPart'][$index]['League'] = $League['League'];
                $ticket['ReservationTicketPart'][$index]['Sport'] = $League['Sport'];

                $ticket['ReservationTicketPart'][$index]['BetPart'] = $Bet['BetPart'];
                $ticket['ReservationTicketPart'][$index]['Bet'] = $Bet['Bet'];
                $ticket['ReservationTicketPart'][$index]['Event'] = $Event['Event'];
                $ticket['ReservationTicketPart'][$index]['Bet']['outcome'] = '';

                if (!empty($correctPick)) {
                    $ticketPart['Bet']['outcome'] = $correctPick['BetPart']['name'];
                }
            }
        }

        return $ticket;
    }

    public function admin_print($id)
    {
        $this->theme = 'design';
        $this->layout = 'user-panel';
        $this->view = 'print_ticket';

        $this->printTicketCommon($id);
    }

    /**
     * Print ticket
     *
     * @param $id
     */
    public function printTicket($id = null)
    {
        $tickets = CakeSession::read('ReservationTicketsIds');

        if (empty($tickets) || !in_array($id, $tickets)) {
            $this->redirect('/', null, true);
        }

        $this->printTicketCommon($id);
    }

    private function printTicketCommon($id)
    {
        $this->ReservationTicket->contain('ReservationTicketPart');

        $Ticket = $this->ReservationTicket->find('first', array(
            'conditions'    =>  array('ReservationTicket.id' => $id)
        ));

        if(empty($Ticket)) {
            $this->App->setMessage(__('Ticket does not exist. Ticket ID: %d', $id), 'error');
            $this->redirect($this->referer(), 302, true);
        }

        if(empty($Ticket)) {
            $this->App->setMessage(__('Ticket does not exist. Reservation Ticket ID: %d', $id), 'error');
            $this->redirect($this->referer(), 302, true);
        }

        foreach ($Ticket['ReservationTicketPart'] AS &$ticketPart) {
            $this->Bet->BetPart->contain('Bet');
            $bet = $this->Bet->BetPart->find('first', array(
                'conditions'    =>  array('BetPart.id' => $ticketPart['bet_part_id'])
            ));
            $event = $this->Event->getItem($bet['Bet']['event_id']);
            $ticketPart['BetPart'] = $bet['BetPart'];
            $ticketPart['Bet'] = $bet['Bet'];
            $ticketPart['Event'] = $event['Event'];
        }

        usort($Ticket['ReservationTicketPart'], function($a1, $a2) {
            $v1 = strtotime($a1['Event']['date']);
            $v2 = strtotime($a2['Event']['date']);
            return $v2 - $v1;
        });

        $this->autoRender = false;

        $view = new View($this, false);
        $view->set('ticket', $Ticket);
        $view->set('currency', Configure::read('Settings.currency'));
        $view->layout = 'printing';

        $pdfFile = TMP . 'tickets' . DS . 'pdf' . DS . 'ticket-' . $id . '.pdf';

        $CacheFolder = new Folder();

        if(!$CacheFolder->cd(TMP . 'reservation-tickets' . DS . 'pdf')) {
            new Folder(TMP . 'tickets' . DS . 'pdf', true, 0777);
        }

        if(!$CacheFolder->cd(TMP . 'reservation-tickets' . DS . 'html')) {
            new Folder(TMP . 'reservation-tickets' . DS . 'html', true, 0777);
        }

        if (isset($this->request->params["pass"][1]) && $this->request->params["pass"][1] == "html") {
            echo $view->render();
            exit;
        }

        $pdf = new File($pdfFile);

        if(!$pdf->exists()) {
            App::import('Vendor', 'dompdf', array('file' => 'dompdf/dompdf_config.inc.php'));

            $domPdf = new DOMPDF();

            $domPdf->set_base_path(realpath(WWW_ROOT));

            $domPdf->set_paper(array(0, 0, 245, 900.00));

            $domPdf->load_html($view->render());

            $domPdf->render();

            $pdf->write($domPdf->output());
        }

        header('Content-type: application/pdf');

        @readfile($pdfFile);

        exit;
    }
}