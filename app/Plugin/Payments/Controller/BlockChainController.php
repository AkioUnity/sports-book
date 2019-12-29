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

class BlockChainController extends PaymentAppController
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'BlockChain';

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
    public $uses = array('Payments.BlockChain', 'Deposit', 'Withdraw');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->BlockChain->getConfig();
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
            $BlockChainData   =   array(
/*                "user_id"       =>  $this->Auth->user('id'),
                "description"   =>  Deposit::DEPOSIT_TYPE_ONLINE,
                "type"          =>  Deposit::DEPOSIT_TYPE_ONLINE,
                'amount'        =>  $this->request->data[$this->name]['amount'],
                "online"        =>  0,
                "date"          =>  date("Y-m-d H:i:s"),
                "deposit_id"    =>  uniqid(),
                "status"    =>  Deposit::DEPOSIT_STATUS_PENDING*/

            );

            $paymentAppData     =   array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  0,
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->BlockChain->name,
                'time'              =>  time()
            );


            $paymentData    =   $this->BlockChain->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($BlockChainData, $paymentAppData);
            $payment_id     =   implode('-', array(
                    0   =>  $paymentData[$this->BlockChain->name]["id"],
                    1   =>  $paymentData[$this->BlockChain->PaymentAppModel->name]["id"]
                )
            );

            $this->config["Config"]["secret"] = uniqid();

            $callback_url = sprintf("%s?test=%s&payment_id=%s&secret=%s", $this->config["Fields"]["callback_url"], $this->config["Config"]["test"], $payment_id, $this->config["Config"]["secret"]);
            $receive_url = sprintf("https://api.blockchain.info/v2/receive?xpub=%s&key=%s&callback=%s", $this->config["Config"]["xpub"], $this->config["Config"]["api_key"], urlencode($callback_url));

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL            => $receive_url,
            ]);
            $response = curl_exec($ch);
            curl_close($ch);

            CakeLog::write('Payments', sprintf("%s SUBMIT_PAYMENT \r\n Data: %s", $this->name,  $response));

            $responseStd = json_decode($response);

            if (!property_exists($responseStd, 'address')) {
                throw new Exception($response);
            }

            $payment = $this->BlockChain->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                'BlockChain'        =>  $paymentData[$this->BlockChain->name]["id"],
                'PaymentAppModel'   =>  $paymentData[$this->BlockChain->PaymentAppModel->name]["id"]
            ));

            $payment["BlockChain"]["address"] = $responseStd->address;
            $payment["BlockChain"]["secret"] = $this->config["Config"]["secret"];

            $this->BlockChain->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($paymentData[$this->BlockChain->name]["id"], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "BlockChain"        =>  $payment["BlockChain"]
            ));


            $this->set('address', $responseStd->address);

        } catch(Exception $e) {
            CakeLog::write('Payments', $this->name . ' : ' . var_export($e->getMessage(), true));
            $this->set('address', "Address cannot be generated. Contact support.");
        }
    }

    public function submitWithdraw()
    {
        if (!$this->request->is('post')) {
            $this->redirect(array('plugin' => false, 'controller' => 'withdraws'), null, true);
        }

        $this->BlockChain->setSource(PaymentAppModel::SOURCE_WITHDRAW)->set($this->request->data);

        if (!$this->BlockChain->validates(array(), PaymentAppModel::SOURCE_WITHDRAW)) {
            $this->App->setMessage(current(current($this->BlockChain->validationErrors)), 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'withdraws'), null, true);
        }

        try {
            $withdrawData = array(
                'user_id'           =>  $this->Auth->user('id'),
                'type'              =>  Withdraw::WITHDRAW_TYPE_ONLINE,
                'description'       =>  __("You have requested withdraw by %s payment provider.", $this->name),
                'payment_provider'  =>  $this->EgoPay->name,
                'withdraw_account'  =>  $this->request->data[$this->name]["account"],
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'date'              =>  gmdate('Y-m-d H:i:s'),
                'status'            =>  Withdraw::WITHDRAW_STATUS_PENDING
            );

            $BlockChainData = array(
                "account"   =>  $this->request->data[$this->name]["account"],
                "amount"    =>  $this->request->data[$this->name]["amount"]
            );

            $paymentAppData = array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->BlockChain->name,
                'time'              =>  time()
            );

            $this->BlockChain->setSource(PaymentAppModel::SOURCE_WITHDRAW)->saveWithdraw($BlockChainData, $withdrawData, $paymentAppData);

            $this->getEventManager()->dispatch(new CakeEvent('Model.Withdraw.addWithdraw', $this, array(
                'userId'    =>  $this->Auth->user('id'),
                'amount'    =>  $this->request->data[$this->name]["amount"]
            )));

            $this->App->setMessage($withdrawData["description"], 'success');

        } catch (Exception $e) {
            $this->App->setMessage(current(current($this->BlockChain->validationErrors)), 'error');
        }

        $this->redirect(array('plugin' => false, 'controller' => 'withdraws'), null, true);
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

            $test = isset($_REQUEST["test"]) ? $_REQUEST["test"] : null;
            $address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : null;
            $secret = isset($_REQUEST["secret"]) ? $_REQUEST["secret"] : null;
            $confirmations = isset($_REQUEST["confirmations"]) ? $_REQUEST["confirmations"] : null;
            $payment_id = isset($_REQUEST["payment_id"]) ? $_REQUEST["payment_id"] : null;
            $transaction_hash = isset($_REQUEST["transaction_hash"]) ? $_REQUEST["transaction_hash"] : null;
            $value_in_btc = isset($_REQUEST["value"]) ? $_REQUEST["value"] / 100000000 : null;


            if (is_null($payment_id)) {
                echo "*ok*";
//                echo "payment_id not provided";
                exit;
            }

            if (is_null($transaction_hash)) {
                echo "*ok*";
//                echo "transaction_hash not provided";
                exit;
            }
            if (is_null($value_in_btc)) {
                echo "*ok*";
//                echo "value_in_btc not provided";
                exit;
            }

            if (!$this->config["Config"]["test"] && $test) {
                echo "*ok*";
//                echo 'Ignoring Test Callback';
                exit;
            }

            if (is_null($confirmations)) {
                echo sprintf("Waiting for confirmations. Got %d", $confirmations);
                exit;
            }

            $payment_id = explode('-', $payment_id);

            if (count($payment_id) != 2) {
                echo "*ok*";
                exit;
            }

            $payment = $this->BlockChain->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                'BlockChain'        =>  $payment_id[0],
                'PaymentAppModel'   =>  $payment_id[1]
            ));

            if (empty($payment) || $payment["PaymentAppModel"]["state"] != self::STATE_PENDING) {
                echo "*ok*";
                exit;
            }

            if ($address != $payment["BlockChain"]["address"]) {
                echo 'Incorrect Receiving Address';
                exit;
            }

            if ($secret != $payment["BlockChain"]["secret"]) {
                echo 'Invalid Secret';
                exit;
            }

            $payment["BlockChain"]["transaction_hash"] = $transaction_hash;
            $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;

            $this->BlockChain->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment_id[0], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "BlockChain"        =>  $payment["BlockChain"]
            ));

            $this->getEventManager()->dispatch(
                new CakeEvent('Controller.PaymentsApp.callback', $this, array(
                    "userId"    =>  $payment["PaymentAppModel"]["userId"],
                    "amount"    =>  $value_in_btc,
                    "state"     =>  $payment["PaymentAppModel"]["state"],
                    "message"   =>  __("You have successfully made payment by %s payment provider.", $this->name),
                ))
            );

            echo "*ok*";
            exit;

        } catch (Exception $e) {
            echo "*bad*";
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
        }

        exit;
    }
}