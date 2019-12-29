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
App::uses('nusoap_client', 'Payments.Lib/Novinpal');

class NovinpalController extends PaymentAppController
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'Novinpal';

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
    public $uses = array('Payments.Novinpal', 'Withdraw', 'Deposit');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->Novinpal->getConfig();
        $this->getEventManager()->attach(new DepositListener());
        $this->getEventManager()->attach(new WithdrawListener());
    }

    /**
     * Index (enter data) action
     */
    public function index()
    {
        $this->layout = 'user-panel';
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
            $NovinpalData = array();

            $paymentAppData = array(
                'userId'            =>  $this->Auth->user('id'),
                'transaction_id'    =>  null,
                'amount'            =>  $this->request->data[$this->name]['amount'],
                'state'             =>  self::STATE_PENDING,
                'model'             =>  $this->Novinpal->name,
                'time'              =>  time()
            );

            $paymentData    =   $this->Novinpal->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($NovinpalData, $paymentAppData);

            $paymentIds     =   array(
                    $this->Novinpal->name                     =>  $paymentData[$this->Novinpal->name]["id"],
                    $this->Novinpal->PaymentAppModel->name    =>  $paymentData[$this->Novinpal->PaymentAppModel->name]["id"]
            );

            $client = new nusoap_client("http://novinpal.com/pay/webservice/?wsdl",true);

            $res = $client->call('requestpayment',array(
                    $this->config['Config']['API'],
                    $this->request->data[$this->name]['amount'], //Tooman
                    $this->config['Config']['callback1'],
                    current($paymentIds) ,
                    sprintf(__("Payment for Deposit %s", implode('-', $paymentIds)))
                )
            );

            if ($res < 0) {
                $this->App->setMessage(__('Wrong amount. Payment Provider Error Code: %s', $res), 'error');
                $this->redirect(array('plugin' => 'payments', 'action' => 'index'), null, true);
            }


            header("location: http://novinpal.com/pay/payment/{$res}");
            exit;
        } catch (Exception $e) {
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
        }
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

            $PAYMENT_ID = $this->request->query('order_id');

            if (!is_numeric($PAYMENT_ID)) {
                throw new Exception(__("Unknown %s payment ID.", $this->name));
            }

            $payment = $this->{$this->Novinpal->name}->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment(array(
                $this->Novinpal->name =>  $PAYMENT_ID
            ));

            if (empty($payment) || $payment["PaymentAppModel"]["state"] != self::STATE_PENDING) {
                 throw new Exception(__("Cannot find payment or its already processed by requested data: %s \r\n Database payment: %s", var_export($this->request->data, true), var_export($payment, true)));
            }

            $client = new nusoap_client("http://novinpal.com/pay/webservice/?wsdl",true);

            $result = $client->call('verification',array(
                $this->config['Config']['API'],
                $payment["PaymentAppModel"]["amount"],
                $this->request->query('au')
            ));

            switch ($result) {
                case 1:
                    $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;
                    break;
                default:
                    $payment["PaymentAppModel"]["state"] = self::STATE_FAILED;
                    break;
            }

            $this->Novinpal->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment["PaymentAppModel"]["id"], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "Novinpal"          =>  $payment["Novinpal"]
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