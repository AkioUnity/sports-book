<?php

App::uses('PaymentAppController', 'Payments.Controller');
App::uses('CoinPaymentsAPI', 'Payments.Lib/CoinPayments');

class CoinPaymentsController extends PaymentAppController
{

    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'CoinPayments';

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
    public $uses = array('Payments.CoinPayment', 'Withdraw', 'Deposit');

    /**
     * @var $api CoinPaymentsAPI
     */
    protected $api;

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->CoinPayment->getConfig();
        $this->getEventManager()->attach(new DepositListener());
        $this->getEventManager()->attach(new WithdrawListener());
        $this->api = new CoinPaymentsAPI();
        $this->api->Setup(
            $this->config['Config']['private_key'],
            $this->config['Config']['public_key']
        );
    }

    public function index()
    {
        $this->layout = 'user-panel';

        $this->request->data[$this->name] = $this->request->query('Deposit');

        $currencies = array();
        $rates = $this->api->GetRates(true, true);

        foreach ($rates['result'] AS $currency => $rate) {
            if ($rate["status"] == "online" && $rate["accepted"] == 1) {
                $currencies[$currency] = $currency;
            }
        }

        $this->set('currencies', $currencies);
    }

    /**
     * Submit payment data
     *
     * @return void
     */
    public function submitPayment()
    {
        if (!$this->request->is('post')) {
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index'), null, true);
        }

        if (!isset($this->request->data[$this->name]['amount']) || !is_numeric($this->request->data[$this->name]['amount'])) {
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index'), null, true);
        }

        if (!isset($this->request->data[$this->name]['currency'])) {
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index'), null, true);
        }

        try {
            $api = $this->api->CreateTransaction(array(
                'amount' => $this->request->data[$this->name]['amount'],
                'currency1' => 'USD', // @TODO: config
                'currency2' => $this->request->data[$this->name]['currency'],
                'ipn_url' => $this->config['Config']['callback'],
                'buyer_email' => CakeSession::read('Auth.User.email'),
            ));

            if ($api["error"] != "ok") {
                $this->App->setMessage($api["error"], 'error');
                $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index'), null, true);
            }

            $CoinPaymentsData = array(
                'transaction_id'    =>  $api["result"]["txn_id"]
            );

            $paymentAppData = array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->CoinPayment->name,
                'time'              =>  time()
            );

            $this->CoinPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($CoinPaymentsData, $paymentAppData);

            header("location: " . $api["result"]["status_url"]);

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
            if ($this->request->data["currency1"] != "USD") {
                throw new InvalidArgumentException('Invalid currency');
            }

            if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
                throw new InvalidArgumentException('IPN Mode is not HMAC');
            }

            if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
                throw new InvalidArgumentException('No HMAC signature sent.');
            }


            $request = http_build_query($this->request->data);

            if ($request === FALSE || empty($request)) {
                throw new InvalidArgumentException('Error reading POST data');
            }

            if (!isset($_POST['merchant']) || $_POST['merchant'] != $this->config['Config']['merchant_id']) {
                throw new InvalidArgumentException('No or incorrect Merchant ID passed');
            }

            $hmac = hash_hmac("sha512", $request, $this->config['Config']['ipn_secret']);

            if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
                throw new InvalidArgumentException('HMAC signature does not match');
            }

            $PAYMENT_ID = $this->request->data('txn_id');

            $payment = $this->{$this->CoinPayment->name}->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                $this->CoinPayment->name =>  $PAYMENT_ID
            ));

            if (empty($payment) || $payment["PaymentAppModel"]["state"] != self::STATE_PENDING) {
                throw new Exception(__("Cannot find payment or its already processed by requested data: %s \r\n Database payment: %s", var_export($this->request->data, true), var_export($payment, true)));
            }

            $status = $this->request->data('status');

            if ($status >= 100 || $status == 2) {
                // payment is complete or queued for nightly payout, success
                $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;


                $this->CoinPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment["PaymentAppModel"]["id"], array(
                    "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                    "CoinPayment"       =>  $payment["CoinPayment"]
                ));

                $this->getEventManager()->dispatch(
                    new CakeEvent('Controller.PaymentsApp.callback', $this, array(
                        "userId"    =>  $payment["PaymentAppModel"]["userId"],
                        "amount"    =>  $payment["PaymentAppModel"]["amount"],
                        "state"     =>  $payment["PaymentAppModel"]["state"],
                        "message"   =>  __("You have successfully made payment by %s payment provider.", $this->name),
                    ))
                );

                die("IPN OK");
            } else if ($status < 0) {
                //payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
            } else {
                //payment is pending, you can optionally add a note to the order page
            }

        } catch (Exception $e) {
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
        }

        exit;
    }
}