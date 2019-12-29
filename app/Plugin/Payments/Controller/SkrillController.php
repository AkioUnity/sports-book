<?php

App::uses('PaymentAppController', 'Payments.Controller');

use Omnipay\Omnipay;

require_once APP . 'Plugin' . DS . 'Payments' . DS . 'Lib' . DS . 'Omnipay' . DS . 'OmniPay.php';

class SkrillController extends PaymentAppController
{

    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'Skrill';

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
    public $uses = array('Payments.Skrill', 'Withdraw', 'Deposit');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->Skrill->getConfig();
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
            $SkrillData = array();

            $paymentAppData = array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->Skrill->name,
                'time'              =>  time()
            );

            $paymentData    =   $this->Skrill->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($SkrillData, $paymentAppData);

            $paymentIds     =   array(
                    $this->Skrill->name                     =>  $paymentData[$this->Skrill->name]["id"],
                    $this->Skrill->PaymentAppModel->name    =>  $paymentData[$this->Skrill->PaymentAppModel->name]["id"]
            );

            $gateway = Omnipay::create('Skrill');

            $transaction = $gateway->purchase([
                'transaction_id'    =>  $paymentIds[$this->Skrill->name],
                'email'             =>  $this->config['Config']['email'],
                'notify_url'        =>  $this->config['Config']['callback1'],
                'return_url'        =>  $this->config['Config']['return_url'],
                'language'          =>  'EN',
                'amount'            =>  floatval($this->request->data[$this->name]['amount']),
                'currency'          =>  'USD',
                'details'           =>  []
            ]);

            $response = $transaction->send();
            $approvalUrl  = $response->getRedirectUrl();


            header("location: $approvalUrl");

            exit;
        } catch (Exception $e) {
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
        }

        $this->redirect(array('plugin' => null, 'controller' => 'deposits', 'action' => 'index'), null, true);
    }

    /**
     * Validate data
     */
    public function callback()
    {
        try {

            if (!$this->request->is('post')) {
                throw new Exception("Not supported request method passed", 500);
            }

            if (empty($this->request->data)) {
                throw new Exception("Empty request data passed", 500);
            }

            $merchantId = $this->request->data("merchant_id");
            $transactionId = $this->request->data('transaction_id');
            $secretWord = strtoupper(md5($this->config['Config']['secret']));
            $mbAmount = $this->request->data('mb_amount');
            $mbCurrency = $this->request->data('mb_currency');
            $status = $this->request->data('status');
            $string = $merchantId.$transactionId.$secretWord.$mbAmount.$mbCurrency.$status;
            $md5sig = strtoupper(md5($string));
            $PAYMENT_ID = $this->request->data('transaction_id');

            if ($md5sig != $this->request->data('md5sig')) {
                throw new Exception(__("Signature validation failed."));
            }

            $payment = $this->{$this->Skrill->name}->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                $this->Skrill->name =>  $PAYMENT_ID
            )); // 2127760.00My

            if (empty($payment) || $payment["PaymentAppModel"]["state"] != self::STATE_PENDING) {
                throw new Exception(__("Cannot find payment or its already processed by requested data: %s \r\n Database payment: %s", var_export($this->request->data, true), var_export($payment, true)));
            }

            switch ($this->request->data('status')) {
                case 2:
                    $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;
                    break;
                case 0:
                    $this->response->statusCode(202);
                    return $this->response;
                    // pending
                    break;
                default:
                    $payment["PaymentAppModel"]["state"] = self::STATE_FAILED;
                    break;
            }

            $this->Skrill->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment["PaymentAppModel"]["id"], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "Skrill"            =>  $payment["Skrill"]
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

        exit;
    }
}