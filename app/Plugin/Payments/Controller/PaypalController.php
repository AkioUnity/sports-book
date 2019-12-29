<?php

App::uses('PaymentAppController', 'Payments.Controller');

require_once APP . 'Plugin' . DS . 'Payments' . DS . 'Lib' . DS . 'Paypal' . DS . 'autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class PayPalController extends PaymentAppController
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'Paypal';

    /**
     * Component
     *
     * @var array
     */
//    public $components = array('Payments.InterSwitch');

    /**
     * Models
     *
     * @var array
     */
    public $uses = array('Payments.PayPalModel', 'Withdraw', 'Deposit');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->PayPalModel->getConfig();
        $this->getEventManager()->attach(new DepositListener());
        $this->getEventManager()->attach(new WithdrawListener());
    }

    /**
     * Submit payment data
     *
     * @return void
     */
    public function submitPayment()
    {
        if (!$this->request->is('post')) {
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name), null, true);
        }

        if (!isset($this->request->data[$this->name]['amount']) || !is_numeric($this->request->data[$this->name]['amount'])) {
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name), null, true);
        }

        try {
            $PaypalData = array();

            $paymentAppData = array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->PayPalModel->name,
                'time'              =>  time()
            );

            $paymentData    =   $this->PayPalModel->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($PaypalData, $paymentAppData);

            $paymentIds     =   array(
                    $this->PayPalModel->name                     =>  $paymentData[$this->PayPalModel->name]["id"],
                    $this->PayPalModel->PaymentAppModel->name    =>  $paymentData[$this->PayPalModel->PaymentAppModel->name]["id"]
            );

            $payer = new Payer();
            $payer->setPaymentMethod("paypal");

            $item1 = new Item();
            $item1->setName('Deposit')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setSku($paymentIds[$this->PayPalModel->name]) // Similar to `item_number` in Classic API
                ->setPrice($this->request->data[$this->name]['amount']);

            $itemList = new ItemList();
            $itemList->setItems(array($item1));

            $details = new Details();

            $amount = new Amount();
            $amount->setCurrency("USD")
                ->setTotal($this->request->data[$this->name]['amount'])
                ->setDetails($details);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription("Deposit")
                ->setInvoiceNumber(uniqid());


            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($this->config['Config']['callback1']);
            $redirectUrls->setCancelUrl($this->config['Config']['callback1']);

            $payment = new Payment();
            $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));

            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    $this->config['Config']["client_id"],
                    $this->config['Config']["client_secret"]
                )
            );


            try {
                $payment->create($apiContext);
            } catch (Exception $e) {
                CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
            }

            $approvalUrl = $payment->getApprovalLink();

            $this->PayPalModel->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($paymentIds[$this->PayPalModel->name], array(
                "payment_id"          =>  $payment->getId()
            ));

            header("location: $approvalUrl");

            exit;
        } catch (Exception $e) {
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
        }

        $this->redirect(array('plugin' => null, 'controller' => 'deposits', 'action' => 'index'), null, true);

        exit;
    }

    /**
     * Validate data
     */
    public function callback()
    {

        try {
            if (!$this->request->is('get')) {
                throw new Exception("Not supported request method passed", 500);
            }

            if (empty($this->request->query)) {
                throw new Exception("Empty request data passed", 500);
            }

            $PAYMENT_ID = $this->request->query('paymentId');
            $PayerID = $this->request->query('PayerID');

            $payment = $this->{$this->PayPalModel->name}->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                $this->PayPalModel->name =>  $PAYMENT_ID
            ));

            if (empty($payment) || $payment["PaymentAppModel"]["state"] != self::STATE_PENDING) {
                throw new Exception(__("Cannot find payment or its already processed by requested data: %s \r\n Database payment: %s", var_export($this->request->data, true), var_export($payment, true)));
            }

            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    $this->config['Config']["client_id"],
                    $this->config['Config']["client_secret"]
                )
            );

            $paymentPaypal = Payment::get($PAYMENT_ID, $apiContext);

            $execution = new \PayPal\Api\PaymentExecution();
            $execution->setPayerId($PayerID);

            $result = $paymentPaypal->execute($execution, $apiContext);
            $result = $result->toArray();

            if (!is_array($result) || empty($result) || $result["id"] != $PAYMENT_ID) {
                throw new Exception(__("Callback URI Id doesn't match response ID. Or response is empty: %s \r\n Database payment: %s", var_export($this->request->data, true), var_export($payment, true)));
            }


            switch ($result["state"]) {
                case "approved":
                    $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;
                    break;
                default:
                    $payment["PaymentAppModel"]["state"] = self::STATE_FAILED;
                    break;
            }

            $this->PayPalModel->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment["PaymentAppModel"]["id"], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "PayPalModel"       =>  $payment["PayPalModel"]
            ));

            $this->getEventManager()->dispatch(
                new CakeEvent('Controller.PaymentsApp.callback', $this, array(
                    "userId"    =>  $payment["PaymentAppModel"]["userId"],
                    "amount"    =>  $payment["PaymentAppModel"]["amount"],
                    "state"     =>  $payment["PaymentAppModel"]["state"],
                    "message"   =>  __("You have successfully made payment by %s payment provider.", $this->name),
                ))
            );

        } catch (Exception $e) {
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
        }

        $this->redirect(array('plugin' => null, 'controller' => 'deposits', 'action' => 'index'), null, true);

        exit;
    }
}