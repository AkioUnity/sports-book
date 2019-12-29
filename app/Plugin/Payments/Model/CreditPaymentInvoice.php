<?php

App::uses('AppModel', 'Model');
App::uses('CreditPaymentInvoiceListener', 'Payments.Event');

class CreditPaymentInvoice extends AppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'CreditPaymentInvoice';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'payments_creditpayments_invoices';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'payments_creditpayments_invoices';

    /**
     * Invoice period 15 value
     */
    const INVOICE_PERIOD_DAY_15           =   15;

    /**
     * Invoice period 22 value
     */
    const INVOICE_PERIOD_DAY_22           =   22;

    /**
     * Invoice period 29 value
     */
    const INVOICE_PERIOD_DAY_29           =   29;

    /**
     * Invoice period 36 value
     */
    const INVOICE_PERIOD_DAY_36           =   36;


    /**
     * Invoice status pending value
     */
    const INVOICE_STATUS_REISSUED        =   "reissued";

    /**
     * Invoice status pending value
     */
    const INVOICE_STATUS_PENDING        =   "pending";

    /**
     * Invoice status transferred value
     */
    const INVOICE_STATUS_TRANSFERRED    =   "transferred";

    /**
     * Invoice status paid value
     */
    const INVOICE_STATUS_PAID           =   "paid";

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array(
        'Deposit'   =>  array(
            'foreignKey'    =>  'deposit_id',
            'type'          =>  'INNER',
            'conditions'    =>  array('CreditPaymentInvoice.deposit_id = Deposit.id')
        ),
        'User' =>  array(
            'foreignKey'    =>  'user_id',
            'type'          =>  'INNER',
            'conditions'    =>  array('CreditPaymentInvoice.user_id = User.id')
        ),
    );

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'            => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => false
        ),
        'user_id'    => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => true
        ),
        'deposit_id'    => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => true
        ),
        'interval'    => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => true
        ),
        'amount'        => array(
            'type'      => 'float',
            'length'    => null,
            'null'      => false
        ),
        'status'=> array(
            'type'      => 'varchar',
            'length'    => 255,
            'null'      => false
        ),
        'time'    => array(
            'type'      => 'bigint',
            'length'    => 9,
            'null'      => true
        ),
    );

    /**
     * List of validation rules.
     *
     * @var array
     */
    public $validate = array();

    /**
     * @var array
     */
    private static $statuses = array(
        'paid'          =>  "Paid",
        'pending'       =>  "Pending",
        "transferred"   =>  "Transferred"
    );

    /**
     * CreditPaymentInvoice constructor.
     * @param bool $id
     * @param null $table
     * @param null $ds
     */
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new CreditPaymentInvoiceListener());
    }

    /**
     * Returns admin scaffold tabs
     *
     * @param array $params - url params
     *
     * @return array
     */
    public function getTabs(array $params)
    {
        $tabs = parent::getTabs($params);
        $tabs["CreditPaymentInvoicesadmin_export"] = array(
            "name"  =>  __("Export"),
            "url"   =>  array(
                "plugin"        =>  "payments",
                "controller"    =>  "CreditPaymentInvoices",
                "action"        =>  "admin_export",
            )
        );
        unset($tabs['CreditPaymentInvoicesadmin_add']);

        $tabs[$params['controller'] . $params['action']]['active'] = 1;
        $tabs[$params['controller'] . $params['action']]['url'] = '#';

        return $tabs;
    }

    /**
     * Returns scaffold actions list
     *
     * @param CakeRequest $cakeRequest - cakeRequest object
     *
     * @return array
     */
    public function getActions(CakeRequest $cakeRequest)
    {
        $actions = parent::getActions($cakeRequest);
        unset($actions[2]);
        return $actions;
    }

    /**
     * Admin edit fields
     *
     * @return mixed
     */
    public function getEdit($data)
    {
        $fields = array(
            'CreditPaymentInvoice.status' =>  $this->getFieldHtmlConfig('select', array('options' => self::$statuses))
        );

        $fields = $this->getBelongings($fields);

        return $fields;
    }

    /**
     * @param array $conditions
     * @return array
     */
    public function getExport($conditions = array())
    {
        return $this->find('all', array(
            'fields'        =>  array(
                'CreditPaymentInvoice.*',
                'CreditPaymentsDeposits.*',
                'Payment.*',
                'Deposit.*',
                'User.*',
            ),
            'conditions'    =>  $conditions,
            'joins'         =>  array(
                array(
                    'table' => 'payments_creditpayments_deposits',
                    'alias' => 'CreditPaymentsDeposits',
                    'type'  => 'inner',
                    'conditions' => array(
                        'payment_app_id = CreditPaymentsDeposits.payment_app_id'
                    )
                ),
                array(
                    'table' => 'payments_payments',
                    'alias' => 'Payment',
                    'type'  => 'inner',
                    'conditions' => array(
                        'payment_app_id = Payment.id'
                    )
                ),
            ),
            'order'         =>  'CreditPaymentInvoice.id DESC'
        ));
    }

    /**
     * @param $id
     * @param $userId
     * @param $period
     * @param $status
     * @return bool|mixed
     */
    public function setStatus($id, $userId, $period, $status)
    {
        $conditions = array(
            'user_id' =>  $userId,
            'interval' =>  $period,
        );


        if (!is_null($id)) {
            $conditions['id'] = $id;
        }
        $invoice = $this->find('first', array(
            'contain'       =>  array(),
            'conditions'    =>  $conditions,
            'recursive'     =>  -1
        ));


        if (!empty($invoice)) {
            $invoice['CreditPaymentInvoice']['status'] = $status;

            return $this->save($invoice);
        }

        return false;
    }

    /**
     * @param array $parent
     * @param $userId
     * @param $depositId
     * @param $interval
     * @param $amount
     * @param $status
     * @param $time
     * @return array|mixed
     */
    public function add($parent, $userId, $depositId, $interval, $amount, $status, $time)
    {
        $parentId = isset($parent[$this->name]["id"]) ? $parent[$this->name]["id"] : null;

        if (!is_null($parentId)) {
            $invoice = $this->find('first', array(
                'contain'       =>  array(),
                'conditions'    =>  array(
                    'parent'        =>  $parentId,
                    'user_id'       =>  $userId,
                    'interval'      =>  $interval,
                ),
                'recursive'     =>  -1
            ));

            if (!empty($invoice)) {
                return $invoice;
            }
        }

        $this->create();

        $data = array(
            $this->name   =>  array(
                'parent'        =>  isset($parent[$this->name]["id"]) ? $parent[$this->name]["id"] : null,
                'user_id'       =>  $userId,
                'deposit_id'    =>  $depositId,
                'interval'      =>  $interval,
                'amount'        =>  $amount,
                'status'        =>  $status,
                'time'          =>  $time
            )
        );

        $data = $this->save($data);

        $this->getEventManager()->dispatch(new CakeEvent('Model.CreditPaymentInvoice.add', $this, $data[$this->name]));

        return $data;
    }
}