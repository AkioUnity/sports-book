<?php
App::uses('AppModel', 'Model');
App::uses('PaymentAppModelInterface', 'Payments.Model/Interface');

class PaymentAppModel extends AppModel implements PaymentAppModelInterface
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'PaymentAppModel';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'payments_payments';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'payments_payments';

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
//    public $actsAs = array('containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $hasOne = array(
        'Deposit'   =>  array(
            'foreignKey'  =>  'payment_app_id'
        ),
        'Withdraw'  =>  array(
            'foreignKey'  =>  'payment_app_id'
        )
    );

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => false
        ),
        'userId'      => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => false
        ),
        'transaction_id'      => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => true
        ),
        'amount'    => array(
            'type'      => 'float',
            'length'    => null,
            'null'      => false
        ),
        'state'    => array(
            'type'      => 'string',
            'length'    => null,
            'null'      => false
        ),
        'model'    => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'time'    => array(
            'type'      => 'int',
            'length'    => 9,
            'null'      => false
        )
    );

    /**
     * Deposit source  value
     */
    const SOURCE_DEPOSIT    =   'deposits';

    /**
     * Withdraw source value
     */
    const SOURCE_WITHDRAW   =   'withdraws';


    public function getPayment($paymentId)
    {
        return $this->find('first', array(
            'contain'       =>  array(),
            'conditions'    =>  array(
                $this->name . '.id' =>  $paymentId
            )
        ));
    }

    /**
     * Returns provider config
     *
     * @param null $modelName
     *
     * @return mixed
     * @throws Exception
     */
    public function getConfig($modelName = null)
    {
        $modelName = is_null($modelName) ? $this->name : $modelName;

        Configure::load('Payments.' . $modelName);

        if (Configure::read($modelName . '.Config') == 0) {
            throw new Exception(__("Config %s.Config not found", $this->name), 500);
        }

        $config = Configure::read($modelName . '.Config');

        if (!isset($config['Environment']) || !isset($config[$config['Environment']])) {
            throw new Exception('Unknown or not found defined config environment!', 500);
        }

        return $config[$config['Environment']];
    }

    public function getAvailableProviders()
    {
        $config = $this->getConfig();
        return $config["availableProviders"];
    }

    /**
     * Saves providers payment data
     *
     * @param array $modelPaymentData - Model provider payment data
     * @param array $paymentAppData   - Global system payment data
     *
     * @return array - payment ids
     */
    public function savePayment(array $modelPaymentData, array $paymentAppData)
    {
        try {
            $this->create();

            $result = $this->saveAll(array(
                $this->name         =>  $modelPaymentData,
                'PaymentAppModel'   =>  $paymentAppData,
            ));

            if (!$result) {
                throw new Exception(
                    sprintf(
                        "\r\n Cannot log %s payment data. \r\n %s Details: %s. \r\n PaymentApp details : %s.",
                        $this->name, $this->name, var_export($modelPaymentData, true), var_export($paymentAppData, true)
                    ),  500
                );
            }

            return $this->find('first', array('conditions' => array(  $this->name . '.id' => $this->id )));

        } catch(Exception $e) {
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Common update payment data method
     *
     * @param int   $paymentId - Payment id
     * @param array $update    - Update data
     *
     * @return mixed
     */
    public function updatePayment($paymentId, array $update)
    {
        $this->create();

        $this->id = $paymentId;

        if (isset($update[$this->name]) && is_array($update[$this->name])) {
            if (isset($update[$this->PaymentAppModel->name]) && is_array($update[$this->PaymentAppModel->name])) {

                return $this->saveAll($update);
            } else {
                return $this->save(array_merge($this->find('first', array('conditions' => array($this->name . '.id' => $paymentId))), array($this->name => $update)));
            }
        }

        return $this->save(array_merge($this->find('first', array('conditions' => array($this->name . '.id' => $paymentId))), array($this->name => $update)));
    }

    public function saveWithdraw(array $modelPaymentData,  array $withdrawData,  array $paymentAppData)
    {
        try {
            $this->create();

            $result = $this->saveAll(array(
                $this->name         =>  $modelPaymentData,
                'Withdraw'          =>  $withdrawData,
                'PaymentAppModel'   =>  $paymentAppData,
            ));

            if (!$result) {
                throw new Exception(
                    sprintf(
                        "\r\n Cannot log %s withdraw data. \r\n %s Details: %s. \r\n PaymentApp details : %s.",
                        $this->name, $this->name, var_export($modelPaymentData, true), var_export($paymentAppData, true)
                    ),  500
                );
            }

            return $this->find('first', array('conditions' => array(  $this->name . '.id' => $this->id )));

        } catch(Exception $e) {
            var_dump($e);
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Extends core functionality
     *
     * Returns true if all fields pass validation.
     *
     * @param array       $options - An optional array of custom options to be made available in the beforeValidate callback
     * @param null|string $source - Source type
     *
     * @return bool
     */
    public function validates($options = array(), $source = null)
    {
        if ($source != null) {
            $config = $this->getConfig();

            if (isset($config["Config"]["validates"][$source])) {
                $validate = $config["Config"]["validates"][$source];

                if (is_array($validate) && !empty($validate)) {
                    $this->validate = $config["Config"]["validates"][$source];

                }
            }
        }

        return parent::validates($options);
    }

    /**
     * Returns validation result of minimum amount field
     *
     * @param array $fields
     *
     * @return bool
     */
    public function validateWithdrawMinAmount(array $fields)
    {
        if (isset($fields["amount"]) && intval($fields["amount"]) >= Configure::read('Settings.minWithdraw')) {
            return true;
        }

        return false;
    }

    /**
     * Returns validation result of minimum amount field
     *
     * @param array $fields
     *
     * @return bool
     */
    public function validateWithdrawMaxAmount(array $fields)
    {
        if (isset($fields["amount"]) && intval($fields["amount"]) <= Configure::read('Settings.maxWithdraw')) {
            return true;
        }

        return false;
    }

    /**
     * Returns validation result of minimum amount field
     *
     * @param array $fields
     *
     * @return bool
     */
    public function validateWithdrawBalance(array $fields)
    {
        if (isset($fields["amount"]) && intval($fields["amount"]) <= CakeSession::read('Auth.User.balance')) {
            return true;
        }

        return false;
    }

    /**
     * Returns validation result of minimum amount field
     *
     * @param array $fields
     *
     * @return bool
     */
    public function validateWithdrawHasPending(array $fields)
    {
        $withdraws = $this->Withdraw->getUserWithdraws(CakeSession::read('Auth.User.id'), array('Withdraw.status' => Withdraw::WITHDRAW_STATUS_PENDING));
        return empty($withdraws);
    }
}