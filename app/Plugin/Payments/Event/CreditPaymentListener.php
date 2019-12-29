<?php

App::uses('CakeEventListener', 'Event');

class CreditPaymentListener implements CakeEventListener
{
    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return array(
            'Listener.CreditPayment.placeTicket'        =>  'onPlaceTicket',
            'Listener.CreditPayment.updateTicketStatus' =>  'onUpdateTicketStatus',
        );
    }

    /**
     * onPlaceTicket
     * Event fired after after new created withdraw
     *
     * @param CakeEvent $event CakeEvent Object
     *
     * @return void
     */
    public function onPlaceTicket(CakeEvent $event)
    {
        try {
            $this->recalc($event->data["user_id"]);
        }catch (Exception $e) {
            CakeLog::write('payments', var_export($e->getMessage(), true));
        }
    }

    /**
     * updateTicketStatus
     * Event fired after after new created withdraw
     *
     * @param CakeEvent $event CakeEvent Object
     *
     * @return void
     */
    public function onUpdateTicketStatus(CakeEvent $event)
    {
        try {
            $this->recalc($event->data["user_id"]);
        }catch (Exception $e) {
            CakeLog::write('payments', var_export($e->getMessage(), true));
        }
    }

    /**
     * Recalculate and update users lost/won amounts
     *
     * @param $userId
     */
    private function recalc($userId)
    {
        $ticketModel = ClassRegistry::init('Ticket');
        $creditPaymentModel = ClassRegistry::init('Payments.CreditPayment');

        $payment = $creditPaymentModel->getCredit($userId);

        if (!empty($payment)) {
            $payment["CreditPayment"]["lost_tickets_amount"] = floatval(current(current($ticketModel->find('first', array(
                "fields"        => array(
                    "SUM(Ticket.amount) AS sum"
                ),
                "conditions"    =>  array(
                    'Ticket.user_id'    =>  $userId,
                    "Ticket.date >="    => gmdate("Y-m-d H:i:s", $payment["PaymentAppModel"]["time"]),
                    "Ticket.status"     =>  array(Ticket::TICKET_STATUS_LOST, Ticket::TICKET_STATUS_PENDING)
                )
            )))));

            $payment["CreditPayment"]["won_tickets_amount"] = floatval(current(current($ticketModel->find('first', array(
                "fields"        => array(
                    "SUM(Ticket.amount) AS sum"
                ),
                "conditions"    =>  array(
                    'Ticket.user_id'    => $userId,
                    "Ticket.date >="    => gmdate("Y-m-d H:i:s", $payment["PaymentAppModel"]["time"]),
                    "Ticket.status"     =>  array(Ticket::TICKET_STATUS_WON)
                )
            )))));

            $creditPaymentModel->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment["PaymentAppModel"]["id"], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "CreditPayment"     =>  $payment["CreditPayment"]
            ));
        }
    }
}