<?php
 /**
 * Etranzact payment data handling model
 *
 * Handles Etranzact payment gateway data
 *
 * @package    Payments
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('PaymentAppModel', 'Payments.Model');

class LunaWallet extends PaymentAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'LunaWallet';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'payments_luna_wallet';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'payments_luna_wallet';

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array(
        'PaymentAppModel'   =>  array(
            'foreignKey'    =>  'payment_app_id',
            'type'          =>  'INNER',
            'conditions'    =>  array('LunaWallet.payment_app_id = PaymentAppModel.id')
        )
    );

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'                => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => false
        ),
        'payment_app_id'    => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => true
        ),
        'payee_account'     => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'payment_amount'    => array(
            'type'      => 'float',
            'length'    => null,
            'null'      => true
        ),
        'payment_units'     => array(
            'type'      => 'string',
            'length'    => 10,
            'null'      => true
        ),
        'payment_batch_num' => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'payer_account'     => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'timestampgmt'      => array(
            'type'      => 'int',
            'length'    => 9,
            'null'      => true
        ),
        'v2_hash'           => array(
            'type'      => 'string',
            'length'    => 128,
            'null'      => true
        )
    );

    public function getPayment($paymentId)
    {
        return $this->find('first', array(
            'conditions'    =>  array(
                $this->name . '.id' =>  $paymentId,
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