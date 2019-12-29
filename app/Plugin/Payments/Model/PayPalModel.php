<?php

App::uses('PaymentAppModel', 'Payments.Model');

class PayPalModel extends PaymentAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'PayPalModel';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'payments_paypal';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'payments_paypal';

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array(
        'PaymentAppModel'   =>  array(
            'foreignKey'    =>  'payment_app_id',
            'type'          =>  'INNER',
            'conditions'    =>  array('PayPalModel.payment_app_id = PaymentAppModel.id')
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
        )
    );

    public function getPayment($paymentId)
    {
        return $this->find('first', array(
            'conditions'    =>  array(
                $this->name . '.payment_id' =>  $paymentId,
            )
        ));
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
     * Generates provider hash key
     *
     * @param array $config - Payment transaction data
     *
     * @return string
     */
    public function generateHash($config)
    {
        return md5(implode('|', $config['Fields']));
    }
}