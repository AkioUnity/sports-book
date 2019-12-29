<?php

App::uses('PaymentAppModel', 'Payments.Model');
App::import('Model','Ticket');

class CreditPayment extends PaymentAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'CreditPayment';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'payments_creditpayments';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'payments_creditpayments';

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array(
        'PaymentAppModel'   =>  array(
            'foreignKey'    =>  'payment_app_id',
            'type'          =>  'INNER',
            'conditions'    =>  array('CreditPayment.payment_app_id = PaymentAppModel.id')
        )
    );

    public $hasOne = array(
        'Deposit'   =>  array(
            'foreignKey'    =>  'payment_app_id'
        )
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
        'payment_id'    => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => true
        ),
        'amount'        => array(
            'type'      => 'float',
            'length'    => null,
            'null'      => false
        ),
        'transaction_id'=> array(
            'type'      => 'varchar',
            'length'    => 255,
            'null'      => false
        ),
        'net_salary'=> array(
            'type'      => 'float',
            'length'    => null,
            'null'      => false
        ),
        'assets'=> array(
            'type'      => 'float',
            'length'    => null,
            'null'      => false
        ),
        'long_term_debt '=> array(
            'type'      => 'float',
            'length'    => null,
            'null'      => false
        ),
        'short_term_debt '=> array(
            'type'      => 'float',
            'length'    => null,
            'null'      => false
        ),
        'cash_in_bank '=> array(
            'type'      => 'float',
            'length'    => null,
            'null'      => false
        ),
        'increase_salary  '=> array(
            'type'      => 'float',
            'length'    => null,
            'null'      => false
        )
    );

    /**
     * List of validation rules.
     *
     * @var array
     */
    public $validate = array();

    public function getLostAmount($userId, $date)
    {
        $query = $this->query("SELECT
                                SUM(`amount`)  as `amount`
                                FROM
                                `tickets`
                                WHERE
                                `user_id` = ?
                                AND
                                `date` >= ?
                                AND
                                `status` IN (?, ?)", array(
                $userId, $date, Ticket::TICKET_STATUS_PENDING, Ticket::TICKET_STATUS_LOST
            )
        );

        return $query[0][0]["amount"];
    }

    /**
     * @param $userId
     * @return array
     *
     * @throws Exception
     */
    public function getCredit($userId)
    {
        $this->primaryKey = 'payment_app_id';

        return $this->setSource(PaymentAppModel::SOURCE_DEPOSIT)->find('first', array(
            'conditions'    =>  array(
                'PaymentAppModel.userId'    =>  $userId,
                'PaymentAppModel.model'     =>  'CreditPayment',
                'PaymentAppModel.state'     =>  'success'
            ),
            'order'         =>  array('PaymentAppModel.id DESC')
        ));
    }

    public function getPayment($paymentId)
    {
        return $this->find('first', array(
            'conditions'    =>  array(
                $this->name . '.id' =>  $paymentId,
            )
        ));
    }

    /**
     * @inheritdoc
     */
    public function getTabs(array $params)
    {
        $tabs = parent::getTabs($params);
        unset($tabs['CreditPaymentadmin_add']);
        return $tabs;
    }

    /**
     * @inheritdoc
     */
    public function getView($id)
    {
        $options['fields'] = array();
        $options['recursive'] = -1;
        $options['conditions'] = array($this->name . '.payment_app_id' => $id);

        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order')) {
                $options['fields'][] = $this->name . '.' . $key;
            }
        }

        return $this->find('first', $options);
    }

    /**
     * Returns EgoPay Provider Config
     *
     * @param null $modelName
     *
     * @return array
     */
    public function getConfig($modelName = null)
    {
        return $this->PaymentAppModel->getConfig($this->name);
    }

    /**
     * @param string $source
     *
     * @return $this|void
     *
     * @throws Exception
     */
    public function setSource($source)
    {
        if ($this->_source != null) {
            return $this;
        }

        switch($source) {
            case self::SOURCE_DEPOSIT:
                parent::setSource($this->table . '_' . self::SOURCE_DEPOSIT);
                break;
            case self::SOURCE_WITHDRAW:
                parent::setSource($this->table . '_' . self::SOURCE_WITHDRAW);
                break;
            default:
                throw new Exception(__("Unknown %s source", $source), 500);
                break;
        }

        $this->_source = $source;

        return $this;
    }

    /**
     * Returns credit payment data if such period doesn't exist!
     *
     * @param $period
     * @return mixed
     */
    public function invoices($period)
    {
        return $this->query("SELECT
                                  `l`.*,
                                  `deposits`.`id` AS `deposit_id`,
                                  `payments_creditpayments_deposits`.`risk_level`,
                                  ( UNIX_TIMESTAMP() - `l`.`time` ) / 60 AS `time_diff`
                                FROM
                                  `payments_payments` `l`
                                  INNER JOIN (
                                               SELECT
                                                 `userId`,
                                                 MAX(`time`) AS `time`
                                               FROM
                                                 `payments_payments`
                                               WHERE
                                                 `model` = 'CreditPayment'
                                                 AND
                                                 `state` = 'success'
                                               GROUP BY
                                                 `userId`
                                             ) r
                                    ON
                                      ( l.`userId` = r.`userId` ) AND ( l.`time` = r.`time` )
                                    INNER JOIN
                                      `payments_creditpayments_deposits`
                                    ON
                                      `l`.`id` = `payments_creditpayments_deposits`.`payment_app_id`
                                    INNER JOIN
                                      `deposits`
                                    ON
                                      `l`.`id` = `deposits`.`payment_app_id`
                                    LEFT JOIN (
                                          SELECT
                                            `id`,
                                            `deposit_id`
                                          FROM
                                          `payments_creditpayments_invoices`
                                          WHERE
                                          `status` IN ( 'paid', 'transferred' )
                                      ) `invoices_paid`
                                    ON
                                      `deposits`.`id` = `invoices_paid`.`deposit_id`
                                    LEFT JOIN (
                                          SELECT
                                            `id`,
                                            `deposit_id`,
                                            `interval`
                                          FROM
                                            `payments_creditpayments_invoices`
                                          WHERE
                                            `interval` = ". $period ."
                                      ) `invoices_interval`
                                    ON
                                      `deposits`.`id` = `invoices_interval`.`deposit_id`
                                WHERE
                                  ( UNIX_TIMESTAMP() - `l`.`time` ) / 60 >= ". $period ."
                                AND
                                  `invoices_paid`.`id` IS NULL
                                AND
                                  `invoices_interval`.`id` IS NULL
                                  ");
    }

    /**
     * Returns payment and pending invoice for period
     * Used for cycles
     *
     * @param $period
     * @return mixed
     */
    public function cycle($period)
    {
        return $this->query("SELECT
                              `l`.*,
                              `deposits`.`id` AS `deposit_id`,
                              `payments_creditpayments_deposits`.`risk_level`,
                              ( UNIX_TIMESTAMP() - `l`.`time` ) / 60 AS `time_diff`,
                              invoices.*
                            FROM
                              `payments_payments` `l`
                              INNER JOIN (
                                           SELECT
                                             `userId`,
                                             MAX(`time`) AS `time`
                                           FROM
                                             `payments_payments`
                                           WHERE
                                             `model` = 'CreditPayment'
                                             AND
                                             `state` = 'success'
                                           GROUP BY
                                             `userId`
                                         ) r
                                ON
                                  ( l.`userId` = r.`userId` ) AND ( l.`time` = r.`time` )
                                INNER JOIN
                                  `payments_creditpayments_deposits`
                                ON
                                  `l`.`id` = `payments_creditpayments_deposits`.`payment_app_id`
                                INNER JOIN
                                  `deposits`
                                ON
                                  `l`.`id` = `deposits`.`payment_app_id`
                               INNER JOIN (
                                    SELECT
                                      `id`,
                                      `deposit_id`,
                                      `interval`,
                                      `time`,
                                      `parent`
                                    FROM
                                    `payments_creditpayments_invoices`
                                    WHERE
                                     ( UNIX_TIMESTAMP() - `time` ) / 60 >= ". $period ."
                                   AND
                                    `status` = 'pending' 
                                    AND
                                      `parent` IS NOT NULL
                                ) `invoices`
                                ON
                                `deposits`.`id` = `invoices`.`deposit_id`");
    }
}