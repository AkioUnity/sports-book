<?php

App::uses('PaymentAppController', 'Payments.Controller');
App::uses('PhpSpreadSheet', 'Lib/PhpSpreadsheet');
App::import('Vendor', 'PhpSpreadSheet', array('file' => 'PhpSpreadsheet/src/Bootstrap.php'));

class CreditPaymentController extends PaymentAppController
{

    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'CreditPayment';

    /**
     * Component
     *
     * @var array
     */
    public $components = array('Payments.CreditPayments');

    /**
     * Models
     *
     * @var array
     */
    public $uses = array(
        'Payments.CreditPayment',
        'Payments.CreditPaymentInvoice',
        'Withdraw',
        'Deposit',
        'Ticket'
    );

    /**
     * Called before the controller action.
     *
     * @return void
     *
     * @throws Exception
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->config = $this->CreditPayment->getConfig();
        $this->getEventManager()->attach(new DepositListener());
        $this->getEventManager()->attach(new WithdrawListener());
    }

    /**
     * @inheritdoc
     */
    public function admin_index($conditions = array(), $model = null)
    {
        $model = "PaymentAppModel";

        $conditions = array(
            "model" =>  "CreditPayment"
        );

        parent::admin_index($conditions, $model);

    }

    /**
     * @inheritdoc
     */
    public function admin_view($id = -1)
    {
        $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT);

        parent::admin_view($id);

        $model = $this->__getModel();

        $this->{$model}->locale = Configure::read('Config.language');

        $data = $this->{$model}->getView($id);

        if (!empty($data)) {
            $data = $this->{$model}->getAdminIndex($data);
            $this->set('fields', $data);
        } else {
            $this->Admin->setMessage(__('Error, not found.', true), 'error');
        }
    }

    /**
     * @inheritdoc
     */
    public function index()
    {
        if ($validationErrors = $this->Session->read('validationErrors')) {
            $this->CreditPayment->validationErrors = $validationErrors;
            $this->Session->delete('validationErrors');
        }

        if ($requestData = $this->Session->read('requestData')) {
            $this->request->data = $requestData;
            $this->Session->delete('requestData');
        }

        if (in_array("no_credit", $this->request->param('pass'))) {
            $this->view = "no_credit";
        }

        if (in_array("not_allowed", $this->request->param('pass'))) {
            $this->view = "not_allowed";
        } else {
            if (!$this->CreditPayments->allowed()) {
                $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index', 'not_allowed'), null, true);
                die();
            }
        }

        if (in_array("credit", $this->request->param('pass')) && count($this->request->param('pass')) == 2) {

            if (!is_numeric($this->request->param('pass')[1])) {
                $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index'), null, true);
            }

            $payment = $this->{$this->CreditPayment->name}->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment($this->request->param('pass')[1]);

            if (empty($payment) || $payment["PaymentAppModel"]["userId"] != $this->Auth->user('id') || $payment["PaymentAppModel"]["state"] != self::STATE_PENDING ) {
                $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index'), null, true);
            }

            if ($this->request->is('put')) {

                $this->{$this->CreditPayment->name}->setSource(PaymentAppModel::SOURCE_DEPOSIT)->set(array_merge_recursive($payment, $this->request->data));

                $this->config["Config"]["validates"]["deposits"]["agree1"] = array(
                    'rule' => array('inList', array('1', 1, 'true', true, 'on')),
                    'message' => 'You need to accept.'
                );

                $this->config["Config"]["validates"]["deposits"]["agree2"] = array(
                    'rule' => array('inList', array('1', 1, 'true', true, 'on')),
                    'message' => 'You need to accept.'
                );

                $this->config["Config"]["validates"]["deposits"]["agree3"] = array(
                    'rule' => array('inList', array('1', 1, 'true', true, 'on')),
                    'message' => 'You need to accept.'
                );

                $this->config["Config"]["validates"]["deposits"]["agree4"] = array(
                    'rule' => array('inList', array('1', 1, 'true', true, 'on')),
                    'message' => 'You need to accept.'
                );

                $this->{$this->CreditPayment->name}->validate = $this->config["Config"]["validates"]["deposits"];

                if ($this->CreditPayment->validates(array(), PaymentAppModel::SOURCE_DEPOSIT)) {

                    $payment["PaymentAppModel"]["state"] = self::STATE_SUCCESS;

                    $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment["PaymentAppModel"]["id"], array(
                        "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                        "CreditPayment"     =>  $payment["CreditPayment"]
                    ));

                    $this->getEventManager()->dispatch(
                        new CakeEvent('Controller.PaymentsApp.callback', $this, array(
                            "payment_app_id"    =>  $payment["PaymentAppModel"]["id"],
                            "userId"            =>  $payment["PaymentAppModel"]["userId"],
                            "amount"            =>  $payment["PaymentAppModel"]["amount"],
                            "state"             =>  $payment["PaymentAppModel"]["state"],
                            "message"   =>  __("Your deposit request was successfully accepted."),
                        ))
                    );

                    $this->redirect(array('plugin' => null, 'controller' => 'deposits', 'action' => 'index'), null, true);
                }
            }

            $payment[$this->CreditPayment->name]["interest_rate"] = sprintf("%s%%", $payment[$this->CreditPayment->name]["interest_rate"]);
            $payment[$this->CreditPayment->name]["amount"] = $payment["PaymentAppModel"]["amount"];

            $this->request->data = $payment;

            $this->view = "credit";
            $this->set('payment', $payment);
        }

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
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index'), null, true);
        }

        $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->set($this->request->data);

        if (!$this->CreditPayment->validates(array(), PaymentAppModel::SOURCE_DEPOSIT)) {
            $this->Session->write('validationErrors', $this->CreditPayment->validationErrors);
            $this->Session->write('requestData',$this->request->data);
            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index'), null, true);
        }

        $creditPayment = $this->request->data('CreditPayment');

        $creditPaymentsData = array(
            "interest_rate"         =>  0,
            "margin_of_safety"      =>  0,
            'net_salary'            =>  $creditPayment["net_salary"],
            'assets'                =>  $creditPayment["assets"],
            'long_term_debt'        =>  $creditPayment["long_term_debt"],
            'short_term_debt'       =>  $creditPayment["short_term_debt"],
            'cash_in_bank'          =>  $creditPayment["cash_in_bank"],
            'increase_salary'       =>  $creditPayment["increase_salary"],
            'starting_balance'      =>  $this->Session->read('Auth.User.balance'),
            'won_tickets_amount'    =>  0,
            'lost_tickets_amount'   =>  0
        );

        $paymentAppData = array(
            'userId'            =>  $this->Auth->user('id'),
            'transaction_id'    =>  null,
            'amount'            =>  null,
            'state'             =>  self::STATE_PENDING,
            'model'             =>  $this->CreditPayment->name,
            'time'              =>  time()
        );

        $payment = $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->savePayment($creditPaymentsData, $paymentAppData);

        try {
            \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder( new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder() );

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( APP . 'Plugin' . DS . 'Payments' . DS . 'Lib' . DS . 'CreditPayment' . DS . 'Payment.xlsx');

            $spreadsheet->setActiveSheetIndex(0)->getCell('K33')->setValue($creditPaymentsData["net_salary"]); // Net Salary / Year
            $spreadsheet->setActiveSheetIndex(0)->getCell('K34')->setValue($creditPaymentsData["assets"]); // Assets
            $spreadsheet->setActiveSheetIndex(0)->getCell('K38')->setValue($creditPaymentsData["long_term_debt"]); // Long term Debt
            $spreadsheet->setActiveSheetIndex(0)->getCell('K39')->setValue($creditPaymentsData["short_term_debt"]); // Short term Debt
            $spreadsheet->setActiveSheetIndex(0)->getCell('K41')->setValue($creditPaymentsData["cash_in_bank"]); // Cash in Bank
            $spreadsheet->setActiveSheetIndex(0)->getCell('K42')->setValue(sprintf("%d%%", $creditPaymentsData["increase_salary"])); // Increase salary

            $payment = $this->{$this->CreditPayment->name}->setSource(PaymentAppModel::SOURCE_DEPOSIT)->getPayment($payment["CreditPayment"]["id"]);

            if ($spreadsheet->getActiveSheet()->getCell('H32')->getCalculatedValue() == "NO CREDIT") {
                $payment["PaymentAppModel"]["state"] = self::STATE_FAILED;

                $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment["PaymentAppModel"]["id"], array(
                    "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                    "CreditPayment"     =>  $payment["CreditPayment"]
                ));

                $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index', 'no_credit'), null, true);
                die();
            }

            $payment["CreditPayment"]["interest_rate"] = $spreadsheet->getActiveSheet()->getCell('I4')->getCalculatedValue() * 100;  // 100 because it is a %
            $payment["CreditPayment"]["margin_of_safety"] = $spreadsheet->getActiveSheet()->getCell('I40')->getCalculatedValue() * 100;  // 100 because it is a %
            $payment["CreditPayment"]["risk_level"] = $spreadsheet->getActiveSheet()->getCell('F4')->getCalculatedValue();
            $payment["PaymentAppModel"]["amount"] = $spreadsheet->getActiveSheet()->getCell('E4')->getCalculatedValue();
            $payment["PaymentAppModel"]["state"] = self::STATE_PENDING;

            if (is_null($payment["CreditPayment"]["risk_level"]) || $payment["CreditPayment"]["risk_level"] == "#N/A" || !is_numeric($payment["PaymentAppModel"]["amount"])) {
                $payment["PaymentAppModel"]["state"] = self::STATE_FAILED;

                $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment["PaymentAppModel"]["id"], array(
                    "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                    "CreditPayment"     =>  $payment["CreditPayment"]
                ));

                $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index', 'no_credit'), null, true);
                die();
            }

            $this->CreditPayment->setSource(PaymentAppModel::SOURCE_DEPOSIT)->updatePayment($payment["PaymentAppModel"]["id"], array(
                "PaymentAppModel"   =>  $payment["PaymentAppModel"],
                "CreditPayment"     =>  $payment["CreditPayment"]
            ));

            $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index', 'credit', $payment["CreditPayment"]["id"]), null, true);
        } catch (Exception $e) {
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
        }

        $this->redirect(array('plugin' => 'payments', 'controller' => $this->name, 'action' => 'index'), null, true);
    }

    /**
     * Validate data
     */
    public function callback() {}
}