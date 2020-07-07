<?php
 /**
 * Ticket Event Listener
 *
 * Holds Ticket Event Listener Methods
 *
 * @package    Tickets
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('CakeEventListener', 'Event');

class TicketListener implements CakeEventListener
{
    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents() {
        return array(
            'Model.Ticket.placeTicket'  =>  'placeTicket',
            'Model.Ticket.updateStatus' =>  'updateStatus'
        );
    }

    /**
     * Place Ticket Event
     * Event fired after ticket placement
     *
     * @param CakeEvent $event
     */
    public function placeTicket(CakeEvent $event)
    {
        try {
            /** @var User $User */
            $User = ClassRegistry::init('User');

            switch(CakeSession::read('Auth.User.Group.id')) {
                case Group::USER_GROUP:
                case Group::ADMINISTRATOR_GROUP:
                case Group::CASHIER_GROUP:
                    $User->addFunds(CakeSession::read('Auth.User.id'), (-1 * $event->data['stake']));
                    break;
                case Group::OPERATOR_GROUP :
                    $User->addFunds(CakeSession::read('Auth.User.id'), (1 * $event->data['stake']));
                    break;
                default:
                    $User->addFunds(CakeSession::read('Auth.User.id'), (-1 * $event->data['stake']));
                    break;
            }

        }catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }

    /**
     * Update Ticket Status Event
     * Event fired after ticket status changed
     *
     * @param CakeEvent $event
     */
    public function updateStatus(CakeEvent $event)
    {

        try {
            if (($event->data['oldStatus'] == Ticket::TICKET_STATUS_PENDING) && ($event->data['newStatus'] == Ticket::TICKET_STATUS_WON)) {
                $event->subject()->User->addFunds($event->data['Ticket']['user_id'], $event->data['Ticket']['return']);
            }
            else if (($event->data['oldStatus'] == Ticket::TICKET_STATUS_PENDING) && ($event->data['newStatus'] == Ticket::TICKET_STATUS_CANCELLED)) {
                $event->subject()->User->addFunds($event->data['Ticket']['user_id'], $event->data['Ticket']['amount']);
            }
            else if (($event->data['oldStatus'] == Ticket::TICKET_STATUS_PENDING) && ($event->data['newStatus'] == Ticket::TICKET_STATUS_LOST)) {
                $this->runcpa($event);
            }
            else if (($event->data['oldStatus'] == Ticket::TICKET_STATUS_LOST) && ($event->data['newStatus'] == Ticket::TICKET_STATUS_WON)) {
                $event->subject()->User->addFunds($event->data['Ticket']['user_id'], $event->data['Ticket']['return']);
            }
            else if (($event->data['oldStatus'] == Ticket::TICKET_STATUS_LOST) && ($event->data['newStatus'] == Ticket::TICKET_STATUS_CANCELLED)) {
                $event->subject()->User->addFunds($event->data['Ticket']['user_id'], $event->data['Ticket']['amount']);
            }
            else if (($event->data['oldStatus'] == Ticket::TICKET_STATUS_WON) && ($event->data['newStatus'] == Ticket::TICKET_STATUS_LOST)) {
                $event->subject()->User->addFunds($event->data['Ticket']['user_id'], - $event->data['Ticket']['return']);
                $this->runcpa($event);
            }
            else if (($event->data['oldStatus'] == Ticket::TICKET_STATUS_WON) && ($event->data['newStatus'] == Ticket::TICKET_STATUS_CANCELLED)) {
                $event->subject()->User->addFunds($event->data['Ticket']['user_id'], - $event->data['oldReturn'] + $event->data['newReturn']);
            }
            else if (($event->data['oldStatus'] == Ticket::TICKET_STATUS_CANCELLED) && ($event->data['newStatus'] == Ticket::TICKET_STATUS_LOST)) {
                $event->subject()->User->addFunds($event->data['Ticket']['user_id'], - $event->data['Ticket']['amount']);
                $this->runcpa($event);
            }
            else if (($event->data['oldStatus'] == Ticket::TICKET_STATUS_CANCELLED) && ($event->data['newStatus'] == Ticket::TICKET_STATUS_WON)) {
                $event->subject()->User->addFunds($event->data['Ticket']['user_id'], $event->data['newReturn'] - $event->data['Ticket']['amount']);
            }
        }catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }

    /**
     * @param CakeEvent $event
     */
    private function runcpa(CakeEvent $event)
    {
        $user = $event->subject()->User->getItem($event->data['Ticket']['user_id'], array('RuncpaModel'));

        if ($user["User"]["balance"] == 0 && !empty($user["RuncpaModel"]))
        {
            $event->subject()->User->Deposit->virtualFields = array(
                'sum'   =>  'SUM(`Deposit`.`amount`)'
            );

            $deposit = $event->subject()->User->Deposit->find('first', array(
                'contain'       =>  array(),
                'conditions'    =>  array(
                    'date >='   =>  $user["RuncpaModel"]["updated"]
                )
            ));

            if (!is_null($deposit["Deposit"]["sum"]))
            {
                $commissions = ($deposit["Deposit"]["sum"] * Configure::read('Settings.commission')) / 100;

                // Track
                RunCpa::getInstance()->generateRevenueShare("rs139490", $commissions);

                // Set last cpa request date
                $event->subject()->User->RuncpaModel->updated($event->data['Ticket']['user_id']);
            }
        }
    }
}