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

App::uses('PaymentAppController', 'Payments.Controller');
App::uses('EgoPaySci', 'Payments.Lib/EgoPay');

class EgoPayController extends PaymentAppController
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'EgoPay';

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
    public $uses = array('Payments.EgoPay', 'Withdraw', 'Deposit');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->EgoPay->getConfig();
        $this->getEventManager()->attach(new DepositListener());
        $this->getEventManager()->attach(new WithdrawListener());
    }

    /**
     * Index (enter data) action
     */
    public function index() {}

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
            $EgoPaySci = new EgoPaySci(array(
                'store_id'          =>  $this->config['Config']['egopay_store_id'],
                'store_password'    =>  $this->config['Config']['egopay_store_password']
            ));

            $egoPayData = array(
                "currency"      =>  $this->config['Fields']['currency'],
                "description"   =>  null
            );

            $paymentAppData = array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->EgoPay->name,
                'time'              =>  time()
            );

            $paymentData    =   $this->EgoPay->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($egoPayData, $paymentAppData);

            $paymentIds     =   array(
                    $this->EgoPay->name                     =>  $paymentData[$this->EgoPay->name]["id"],
                    $this->EgoPay->PaymentAppModel->name    =>  $paymentData[$this->EgoPay->PaymentAppModel->name]["id"]
            );

            $this->config['Fields']['cf_1']         = implode('-', $paymentIds);
            $this->config['Fields']['amount']       = $this->request->data[$this->name]['amount'];
            $this->config['Fields']['description']  = sprintf(__("Payment for Deposit %s", implode('-', $paymentIds)));

            $paymentHash = $EgoPaySci->createHash($this->config['Fields']);

            $this->EgoPay->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($paymentIds[$this->EgoPay->name], array(
                "description"   =>  $this->config['Fields']['description'],
                "hash"          =>  $paymentHash
            ));

            $this->set('submitUrl', $this->config['Config']['submit_url']);
            $this->set('fields', array('hash' => $paymentHash));

        } catch (Exception $e) {

        }
    }

    public function submitWithdraw()
    {
        if (!$this->request->is('post')) {
            $this->redirect(array('plugin' => false, 'controller' => 'withdraws'), null, true);
        }

        $this->EgoPay->setSource(PaymentAppModel::SOURCE_WITHDRAW)->set($this->request->data);

        if (!$this->EgoPay->validates(array(), PaymentAppModel::SOURCE_WITHDRAW)) {
            $this->App->setMessage(current(current($this->EgoPay->validationErrors)), 'error');
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

            $egoPayData = array(
                "account"   =>  $this->request->data[$this->name]["account"],
                "amount"    =>  $this->request->data[$this->name]["amount"]
            );

            $paymentAppData = array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->EgoPay->name,
                'time'              =>  time()
            );

            $this->EgoPay->setSource(PaymentAppModel::SOURCE_WITHDRAW)->saveWithdraw($egoPayData, $withdrawData, $paymentAppData);

            $this->getEventManager()->dispatch(new CakeEvent('Model.Withdraw.addWithdraw', $this, array(
                'userId'    =>  $this->Auth->user('id'),
                'amount'    =>  $this->request->data[$this->name]["amount"]
            )));

            $this->App->setMessage($withdrawData["description"], 'success');

        } catch (Exception $e) {
            $this->App->setMessage(current(current($this->EgoPay->validationErrors)), 'error');
        }

        $this->redirect(array('plugin' => false, 'controller' => 'withdraws'), null, true);
    }

    public function success() { }
    public function test() { }
    /**
     * Validate data
     */
    public function callback()
    {
        CakeLog::write('Payments', $this->name . ' : ' . var_export($_POST, true));

        try {
            if (!$this->request->is('post')) {
                throw new Exception("Not supported request method passed", 500);
            }

            if (empty($this->request->data)) {
                throw new Exception("Empty request data passed", 500);
            }

            $EgoPay = new EgoPaySciCallback(array(
                'store_id'          =>  $this->config['Config']['egopay_store_id'],
                'store_password'    =>  $this->config['Config']['egopay_store_password']
            ));

            $this->request->data = $EgoPay->getResponse($this->request->data);

            $fieldsDiff = array_diff($this->config['Config']['callback_fields'], array_keys($this->request->data));

            if (!empty($fieldsDiff)) {
                throw new Exception(__("Wrong callback params passed. Required: %s \r\n Passed: %s",
                    var_export($this->config['Config']['callback_fields'], true),
                    var_export($this->request->data, true)
                    )
                );
            }

            $PAYMENT_ID = explode('-', $this->request->data["cf_1"]);

            if (count($PAYMENT_ID) != 2) {
                throw new Exception(__("Unknown %s payment ID. Expected IDS got %s", $this->name, var_export($PAYMENT_ID, true)));
            }

            $payment = $this->{$this->EgoPay->name}->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                $this->EgoPay->name =>  $PAYMENT_ID[0],
                'PaymentAppModel'   =>  $PAYMENT_ID[1]
            ));

            if (empty($payment) || $payment["PaymentAppModel"]["state"] != self::STATE_PENDING) {
                 throw new Exception(__("Cannot find payment or its already processed by requested data: %s \r\n Database payment: %s", var_export($this->request->data, true), var_export($payment, true)));
            }

            if ($payment[$this->EgoPay->name]["currency"] != $this->request->data["sCurrency"]) {
                throw new Exception(__("Invalid payment currency by requested data: %s \r\n Database payment: %s", var_export($this->request->data, true), var_export($payment, true)));
            }

            if ($payment["PaymentAppModel"]["amount"] != $this->request->data["fAmount"]) {
                throw new Exception(__("Invalid payment amount by requested data: %s \r\n Database payment: %s", var_export($this->request->data, true), var_export($payment, true)));
            }

            switch ($this->request->data['sStatus']) {
                case 'Completed':
                    $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;
                    break;
                case 'TEST SUCCESS':
                    $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;
                    break;
                case 'Pending':
                    $payment["PaymentAppModel"]["state"] = self::STATE_PENDING;
                    break;
                case 'On Hold':
                    $payment["PaymentAppModel"]["state"] = self::STATE_PENDING;
                    break;
                case 'Canceled':
                    $payment["PaymentAppModel"]["state"] = self::STATE_FAILED;
                    break;
            }

            $this->EgoPay->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($PAYMENT_ID[0], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "EgoPay"            =>  $payment["EgoPay"]
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