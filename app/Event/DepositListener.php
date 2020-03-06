<?php
 /**
 * Users Event Listener
 *
 * Holds Users Event Listener Methods
 *
 * @package    Users
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('CakeEventListener', 'Event');

class DepositListener implements CakeEventListener
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
            'Controller.PaymentsApp.callback'   =>  'onPaymentCallback',
            'Model.Deposit.setStatus'           =>  'onSetStatus',
        );
    }

    /**
     * Update user balance
     * Event fired after after new created withdraw
     *
     * @param CakeEvent $event CakeEvent Object
     *
     * @return void
     */
    public function onSetStatus(CakeEvent $event)
    {
        try{

            switch($event->data['status']) {
                case Deposit::DEPOSIT_STATUS_COMPLETED:
                    $event->subject()->User->addBalance( $event->data['userId'], abs($event->data['amount']), $event->data['paymentCallback'] );

                    break;
            }
        }
        catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }

    /**
     * Update user deposit
     * Event fired after payment callback
     *
     * @param CakeEvent $event CakeEvent Object
     *
     * @return void
     */
    public function onPaymentCallback(CakeEvent $event)
    {
        try {
            switch ($event->data["state"]) {
                case PaymentAppController::STATE_PENDING:
                    break;

                case PaymentAppController::STATE_SUCCESS:

                    $depositData = $event->subject()->Deposit->addDeposit(
                        $event->data["userId"],
                        $event->data["amount"],
                        null,
                        Deposit::DEPOSIT_TYPE_ONLINE,
                        $event->data['message'],
                        Deposit::DEPOSIT_STATUS_PENDING
                    );

                    $event->subject()->Deposit->setStatus($depositData["Deposit"]["id"], Deposit::DEPOSIT_STATUS_COMPLETED, true);

                    break;

                case PaymentAppController::STATE_FAILED:
                    break;
            }
        } catch (Exception $e) {

        }

    }
}