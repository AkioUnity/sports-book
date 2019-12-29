<?php
 /**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */ 

class CreditPaymentsComponent extends Component
{
    /**
     *
     * Allowed to request new credit if:
     *
     * 1. Current amount = credit start payment or no previous payment
     * 2. No pending tickets
     * 3. No pending invoices
     *
     * @return bool
     */
    public function allowed()
    {
        $credit = $this->_Collection->getController()->CreditPayment->getCredit(
            $this->_Collection->getController()->Auth->user('id')
        );

        // No previous credit
        if (empty($credit)) {
            return true;
        }

        // Balance
        if (CakeSession::read('Auth.User.balance') != 0) {
            if ($credit["CreditPayment"]["starting_balance"] != CakeSession::read('Auth.User.balance')) {
                return false;
            }
        }

        // Pending tickets
        $pending = current(current($this->_Collection->getController()->Ticket->find('first', array(
            'contain'       =>  array(),
            "fields"        => array(
                "COUNT(Ticket.id) AS total"
            ),
            'conditions'    =>  array(
                'Ticket.user_id'    =>  $this->_Collection->getController()->Auth->user('id'),
                "Ticket.date >="    => gmdate("Y-m-d H:i:s", $credit["PaymentAppModel"]["time"]),
                "Ticket.status"     =>  array(Ticket::TICKET_STATUS_PENDING)
            )
        ))));

        // CreditPaymentInvoice
        if ($pending != 0) {
            return false;
        }

        // Pending invoices?
        $not_paid = current(current($this->_Collection->getController()->CreditPaymentInvoice->find('first', array(
            'contain'       =>  array(),
            "fields"        => array(
                "COUNT(CreditPaymentInvoice.id) AS total"
            ),
            'conditions'    =>  array(
                'CreditPaymentInvoice.user_id'      =>  $this->_Collection->getController()->Auth->user('id'),
                "CreditPaymentInvoice.deposit_id"   =>  $credit["Deposit"]["id"],
                "CreditPaymentInvoice.status"       =>  array(CreditPaymentInvoice::INVOICE_STATUS_PENDING, CreditPaymentInvoice::INVOICE_STATUS_TRANSFERRED)
            )
        ))));

        if ($not_paid != 0) {
            return false;
        }

        return true;
    }
}