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

class WithdrawListener implements CakeEventListener
{
    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents() {
        return array(
            'Model.Withdraw.addWithdraw'            =>  'onAddWithdraw',
            'Controller.PaymentsApp.submitWithdraw' =>  'onSubmitWithdraw'
        );
    }

    /**
     * Update user balance
     * Event fired after after new created withdraw
     *
     * @param CakeEvent $event
     */
    public function onAddWithdraw(CakeEvent $event) {
        try{
            $event->subject()->User->addBalance( $event->data['userId'], -($event->data['amount']), true );
        }
        catch (Exception $e) {}
    }

    /**
     * Update user balance
     * Event fired after after new created withdraw
     *
     * @param CakeEvent $event
     */
    public function onSubmitWithdraw(CakeEvent $event) {
        try {
            $event->subject()->Withdraw->addWithdraw(
                $event->data["userId"],
                '-',
                $event->data["amount"],
                Withdraw::WITHDRAW_TYPE_ONLINE,
                $event->data["message"],
                $event->data["state"]
            );
        } catch (Exception $e) {}
    }
}