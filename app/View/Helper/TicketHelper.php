<?php

App::uses('AppHelper', 'View/Helper');
App::uses('Ticket', 'Model');

class TicketHelper extends AppHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'Ticket';

    /**
     * Ticket Model Object
     *
     * @var $c Ticket
     */
    private $Ticket = null;

    /**
     * Helpers list
     *
     * @var array
     */
    public $helpers = array(
        0   =>  'Ajax',
        1   =>  'Session',
        2   =>  'Time'
    );

    /**
     * Default Constructor
     *
     * @param View $View The View this helper is being attached to.
     * @param array $settings Configuration settings for the helper.
     */
    public function __construct(View $View, $settings = array()) {
        $this->Ticket = new Ticket();
        parent::__construct($View, $settings);
    }

    /**
     * Maps ticket status with model
     *
     * @param $status
     * @return mixed
     */
    public function getStatus($status) {
        return $this->Ticket->getTicketStatus($status);
    }

    /**
     * Maps ticket <...> with model
     *
     * @param $ticketId
     * @return bool
     */
    public function isCancellable($ticketId) {
        return $this->Ticket->isCancellable($ticketId);
    }

    /**
     * isAllowedToPlaceTicket wrapper
     *
     * @return mixed
     */
    public function isAllowedToPlaceTicket() {
        if(CakeSession::read('Auth.User') == null) {
            return true;
        }

        return CakeSession::read('Auth.User.allow_betslip') == 1;
    }

    /**
     * assignTicketPaid wrapper to model
     *
     * @param null $ticketPaidStatus
     * @param $ticket
     * @return string
     */
    public function assignTicketPaid($ticketPaidStatus = null, $ticket){
        return $this->Ticket->assignTicketPaid($ticketPaidStatus, $ticket);
    }
}