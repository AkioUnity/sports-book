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

class PerfectMoneyController extends PaymentAppController
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'PerfectMoney';

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
    public $uses = array('Payments.PerfectMoney', 'Deposit', 'Withdraw');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->PerfectMoney->getConfig();
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
            $perfectMoneyData   =   array();

            $paymentAppData     =   array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->PerfectMoney->name,
                'time'              =>  time()
            );

            $paymentData                                =   $this->PerfectMoney->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($perfectMoneyData, $paymentAppData);

            $this->config['Fields']['PAYMENT_AMOUNT']   =   $paymentAppData['amount'];
            $this->config['Fields']['PAYMENT_ID']       =   implode('-', array(
                    0   =>  $paymentData[$this->PerfectMoney->name]["id"],
                    1   =>  $paymentData[$this->PerfectMoney->PaymentAppModel->name]["id"]
                )
            );
        } catch(Exception $e) {

        }

        $this->set('submitUrl', $this->config['Config']['submit_url']);
        $this->set('fields', $this->config['Fields']);
    }

    public function submitWithdraw()
    {
        if (!$this->request->is('post')) {
            $this->redirect(array('plugin' => false, 'controller' => 'withdraws'), null, true);
        }

        $this->PerfectMoney->setSource(PaymentAppModel::SOURCE_WITHDRAW)->set($this->request->data);

        if (!$this->PerfectMoney->validates(array(), PaymentAppModel::SOURCE_WITHDRAW)) {
            $this->App->setMessage(current(current($this->PerfectMoney->validationErrors)), 'error');
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

            $perfectMoneyData = array(
                "account"   =>  $this->request->data[$this->name]["account"],
                "amount"    =>  $this->request->data[$this->name]["amount"]
            );

            $paymentAppData = array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->PerfectMoney->name,
                'time'              =>  time()
            );

            $this->PerfectMoney->setSource(PaymentAppModel::SOURCE_WITHDRAW)->saveWithdraw($perfectMoneyData, $withdrawData, $paymentAppData);

            $this->getEventManager()->dispatch(new CakeEvent('Model.Withdraw.addWithdraw', $this, array(
                'userId'    =>  $this->Auth->user('id'),
                'amount'    =>  $this->request->data[$this->name]["amount"]
            )));

            $this->App->setMessage($withdrawData["description"], 'success');

        } catch (Exception $e) {
            $this->App->setMessage(current(current($this->PerfectMoney->validationErrors)), 'error');
        }

        $this->redirect(array('plugin' => false, 'controller' => 'withdraws'), null, true);
    }

    public function success() { }

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

            if (isset($this->request->data["ERROR"])) {
                throw new Exception(__("Error in callback request passed: %s", (string) $this->request->data["ERROR"]), 500);
            }

            $fieldsDiff = array_diff($this->config['Config']['callback_fields'], array_keys($this->request->data));

            if (!empty($fieldsDiff)) {
                throw new Exception(__("Wrong callback params passed. Required: %s \r\n Passed: %s",
                    var_export($this->config['Config']['callback_fields'], true),
                    var_export($this->request->data, true)
                    )
                );
            }

            $PAYMENT_ID = explode('-', $this->request->data["PAYMENT_ID"]);

            if (count($PAYMENT_ID) != 2) {
                throw new Exception(__("Unknown %s payment ID. Expected IDS got %s", $this->name, var_export($PAYMENT_ID, true)));
            }

            $payment = $this->PerfectMoney->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                'PerfectMoney'      =>  $PAYMENT_ID[0],
                'PaymentAppModel'   =>  $PAYMENT_ID[1]
            ));

            if (empty($payment) || $payment["PaymentAppModel"]["state"] != self::STATE_PENDING) {
                 throw new Exception(__("Cannot find payment or its already processed by requested data: %s \r\n Database payment: %s", var_export($this->request->data, true), var_export($payment, true)));
            }

            $payment["PerfectMoney"] = array_merge($payment["PerfectMoney"], array_change_key_case($this->request->data, CASE_LOWER ));

            $hash = function($object) {
                $string =
                    $object->request->data['PAYMENT_ID']        .':'    .   $object->request->data['PAYEE_ACCOUNT'].':'.
                    $object->request->data['PAYMENT_AMOUNT']    .':'    .   $object->request->data['PAYMENT_UNITS'].':'.
                    $object->request->data['PAYMENT_BATCH_NUM'] .':'    .
                    $object->request->data['PAYER_ACCOUNT']     .':'    .   strtoupper(md5($object->config['Config']['signature'])).':'.
                    $object->request->data['TIMESTAMPGMT'];

                return strtoupper(md5($string));
            };

            switch ($this->request->data["V2_HASH"] == $hash($this)) {
                case true:
                    $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;
                    break;
                default:
                    $payment["PaymentAppModel"]["state"] = self::STATE_FAILED;
                    break;
            }

            $this->PerfectMoney->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($PAYMENT_ID[0], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "PerfectMoney"      =>  $payment["PerfectMoney"]
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