<?php
 /**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */ 

interface PaymentAppModelInterface
{
    public function savePayment(array $modelPaymentData, array $paymentAppData);

    public function saveWithdraw(array $modelPaymentData, array $withdrawData, array $paymentAppData);

    public function setSource($tableName);

    public function getConfig($modelName = null);

    public function validateWithdrawMinAmount(array $fields);

    public function validateWithdrawMaxAmount(array $fields);

    public function validateWithdrawBalance(array $fields);

    public function validateWithdrawHasPending(array $fields);
}