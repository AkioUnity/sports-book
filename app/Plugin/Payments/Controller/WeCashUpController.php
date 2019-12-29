<?php

App::uses('PaymentAppController', 'Payments.Controller');
App::uses('CoinPaymentsAPI', 'Payments.Lib/CoinPayments');

class WeCashUpController extends PaymentAppController
{

    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'WeCashUp';

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
    public $uses = array('Payments.WeCashUp', 'Withdraw', 'Deposit');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->WeCashUp->getConfig();
        $this->getEventManager()->attach(new DepositListener());
        $this->getEventManager()->attach(new WithdrawListener());
        $this->Auth->allow(array('callback', 'webhook'));
    }

    public function index()
    {
        $this->layout = 'user-panel';

        $this->request->data[$this->name] = $this->request->query('Deposit');

        $amount = isset($this->request->data[$this->name]["amount"]) ? $this->request->data[$this->name]["amount"] : 0;

        $this->set('amount', floatval($amount));
        $this->set('config', $this->config["Config"]);
    }

    /**
     * Submit payment data
     *
     * @return void
     */
    public function submitPayment()
    {
        $this->redirect(array('plugin' => null, 'controller' => 'deposits', 'action' => 'index'), null, true);
    }

    public function webhook()
    {
        CakeLog::write('Payments', "Webhook log: \r\n");
        CakeLog::write('Payments', var_export($_REQUEST, true));

        exit;
    }

    /**
     * Validate data
     */
    public function callback()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');

        $merchant_uid = $this->config["Config"]["merchant_uid"];
        $merchant_public_key = $this->config["Config"]["merchant_public_key"];
        $merchant_secret = $this->config["Config"]["merchant_secret"];

        $transaction_uid = $this->request->data('transaction_uid');
        $transaction_token  = $this->request->data('transaction_token');
        $transaction_provider_name  = $this->request->data('transaction_provider_name');
        $transaction_confirmation_code  = $this->request->data('transaction_confirmation_code');

        $url = 'https://www.wecashup.com/api/v2.0/merchants/'.$merchant_uid.'/transactions/'.$transaction_uid.'?merchant_public_key='.$merchant_public_key;

        //Step 8 : Sending data to the WeCashUp Server
        $fields = array(
            'merchant_secret'               =>  urlencode($merchant_secret),
            'transaction_token'             =>  urlencode($transaction_token),
            'transaction_uid'               =>  urlencode($transaction_uid),
            'transaction_confirmation_code' =>  urlencode($transaction_confirmation_code),
            'transaction_provider_name'     =>  urlencode($transaction_provider_name),
            'customers_email'               =>  urlencode("test@example.com"),
            '_method'                       =>  urlencode('PATCH')
        );

        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Step 9  : Retrieving the WeCashUp Response
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        $data = json_decode($server_output, true);

        if($data['response_status'] =="success") {
            //Steps 7 : You must complete this script at this to save the current transaction in your database.
            try {
                $WeCashUpData = array(
                    'transaction_uid'               =>  $transaction_uid,
                    'transaction_confirmation_code' =>  $transaction_confirmation_code,
                    'transaction_token'             =>  $transaction_token,
                    'transaction_provider_name'     =>  $transaction_provider_name,
                    'transaction_status'            =>  self::STATE_PENDING
                );

                $paymentAppData = array(
                    'userId'            =>   $data["response_content"]["transaction"]["transaction_sender_reference"],
                    'transaction_id'    =>  $transaction_uid,
                    'amount'            =>  $data["response_content"]["transaction"]["transaction_receiver_total_amount"],
                    'state'             =>  self::STATE_PENDING,
                    'model'             =>  $this->WeCashUp->name,
                    'time'              =>  time()
                );

                // Accept payment only if it is USD
                if ($data["response_content"]["transaction"]["transaction_receiver_currency"] == "USD") {
                    $this->WeCashUp->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($WeCashUpData, $paymentAppData);
                }

            } catch (Exception $e) {
                CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
            }

            //Do wathever you want to tell the user that it's transaction succeed or redirect him/her to a success page
            $location = 'https://www.wecashup.cloud/cdn/tests/websites/PHP/responses_pages/success.html';
        }else{

            //Do wathever you want to tell the user that it's transaction failed or redirect him/her to a failure page
            $location = 'https://www.wecashup.cloud/cdn/tests/websites/PHP/responses_pages/failure.html';
        }

       CakeLog::write('Payments', var_export($_REQUEST, true));
       CakeLog::write('Payments', var_export($data, true));

        // redirect to your feedback page
        echo '<script>top.window.location = "'.$location.'"</script>';

        exit;
    }
}