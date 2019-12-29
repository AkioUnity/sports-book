<?php
 /**
 * Handles e-tranzact payments
 *
 * Long description for class (if any)...
 *
 * @package    Payments
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('Deposit', 'Model');
App::uses('PaymentAppController', 'Payments.Controller');

class LunaWalletController extends PaymentAppController
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'LunaWallet';

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
    public $uses = array('Payments.LunaWallet', 'Deposit', 'Withdraw');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->LunaWallet->getConfig();
        $this->getEventManager()->attach(new DepositListener());
        $this->getEventManager()->attach(new WithdrawListener());
        $this->layout = 'user-panel';
    }

    /**
     * Index (enter data) action
     */
    public function index() {
        $this->submitPayment();
    }

    /**
     * Submit payment data
     *
     * @return void
     */
    public function submitPayment()
    {
        try {
            $LunaWalletData   =   array(
                "secret"        =>  uniqid(),
                "address"       =>  null
            );

            $paymentAppData     =   array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  str_replace(',', '.', $this->request->query('Deposit.amount')),
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->LunaWallet->name,
                'time'              =>  time()
            );

            $paymentData    =   $this->LunaWallet->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($LunaWalletData, $paymentAppData);


            $payment_id     =   implode('-', array(
                    0   =>  $paymentData[$this->LunaWallet->name]["id"],
                    1   =>  $paymentData[$this->LunaWallet->PaymentAppModel->name]["id"]
                )
            );

            $callback_url = sprintf("%s?test=%s&payment_id=%s&secret=%s", $this->config["Fields"]["callback_url"], $this->config["Config"]["test"], $payment_id, $LunaWalletData["secret"]);
            $receive_url = sprintf("%s?api_key=%s&amount=%s&callback=%s", $this->config["Config"]["lunawallet_root"], $this->config["Config"]["api_key"], $paymentAppData['amount'], urlencode($callback_url));

            $ch = curl_init();
            $timeout = 30;
            curl_setopt($ch, CURLOPT_URL, $receive_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $response = curl_exec($ch);
            curl_close($ch);

            CakeLog::write('Payments', sprintf("%s SUBMIT_PAYMENT \r\n Data: %s", $this->name,  $response));

            $responseStd = json_decode($response);

            if (!property_exists($responseStd, 'address')) {
                throw new Exception($response);
            }

            $payment = $this->LunaWallet->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                'LunaWallet'        =>  $paymentData[$this->LunaWallet->name]["id"],
                'PaymentAppModel'   =>  $paymentData[$this->LunaWallet->PaymentAppModel->name]["id"]
            ));

            $payment["LunaWallet"]["address"] = $responseStd->address;

            $this->LunaWallet->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($paymentData[$this->LunaWallet->name]["id"], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "LunaWallet"        =>  $payment["LunaWallet"]
            ));


            $this->set('response', $responseStd);

        } catch(Exception $e) {
            var_dump($e); exit;
            CakeLog::write('Payments', $this->name . ' : ' . var_export($e->getMessage(), true));
            $this->set('address', "Address cannot be generated. Contact support.");
        }
    }

    public function submitWithdraw()
    {
    }

    public function success() { }

    /**
     * Validate data
     */
    public function callback()
    {
        CakeLog::write('Payments', sprintf("%s CALLBACK \r\n Data: %s", $this->name,  var_export($_REQUEST, true)));

        try {
            if (empty($_REQUEST)) {
                throw new Exception("Empty request data passed", 500);
            }

            $txid = isset($_REQUEST["txid"]) ? $_REQUEST["txid"] : null;
            $payment_id = isset($_REQUEST["payment_id"]) ? $_REQUEST["payment_id"] : null;
            $confirmations = isset($_REQUEST["confirmations"]) ? $_REQUEST["confirmations"] : null;
            $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : null;
            $secret = isset($_REQUEST["secret"]) ? $_REQUEST["secret"] : null;
            $hash = isset($_REQUEST["hash"]) ? $_REQUEST["hash"] : null;
            $amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : 0;

            if(hash('sha1', $txid . "-" . $this->config["Config"]["api_key"] . "-" . $this->config["Config"]["username"]) != $hash) {
                echo 'Invalid callback ping.'; //And, a possible attempt to mess with your callback handler.
                exit;
            }

            if($confirmations < 3) {
                echo '[false]';
                exit;
            }

            if (is_null($payment_id)) {
                echo '[true]';
                exit;
            }

            $payment_id = explode('-', $payment_id);

            if (count($payment_id) != 2) {
                echo '[true]';
                exit;
            }

            $payment = $this->LunaWallet->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                'LunaWallet'        =>  $payment_id[0],
                'PaymentAppModel'   =>  $payment_id[1]
            ));

            if (empty($payment) || $payment["PaymentAppModel"]["state"] != self::STATE_PENDING) {
                echo '[true]';
                exit;
            }

            if ($address != $payment["LunaWallet"]["address"]) {
                echo '[true]';
                exit;
            }

            if ($secret != $payment["LunaWallet"]["secret"]) {
                echo '[true]';
                exit;
            }

            if ($amount != $payment["PaymentAppModel"]["amount"]) {
                echo '[true]';
                exit;
            }

            $payment["LunaWallet"]["transaction_hash"] = $hash;
            $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;

            $this->LunaWallet->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment_id[0], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "LunaWallet"        =>  $payment["LunaWallet"]
            ));

            $this->getEventManager()->dispatch(
                new CakeEvent('Controller.PaymentsApp.callback', $this, array(
                    "userId"    =>  $payment["PaymentAppModel"]["userId"],
                    "amount"    =>  $payment["PaymentAppModel"]["amount"],
                    "state"     =>  $payment["PaymentAppModel"]["state"],
                    "message"   =>  __("You have successfully made payment by %s payment provider.", $this->name),
                ))
            );

            echo '[true]';
            exit;

        } catch (Exception $e) {
            echo '[false]';
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
        }

        exit;
    }
}