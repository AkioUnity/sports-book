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

class InterSwitch extends PaymentAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'InterSwitch';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'payments_interswitch';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'payments_interswitch';

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array('PaymentAppModel');

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
        'terminal_id'      => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => true
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
            'type'      => 'enum',
            'length'    => null,
            'null'      => true
        )
    );

    /**
     * Saves providers payment data
     *
     * @param array $interSwitchData - InterSwitch provider payment data
     * @param array $paymentAppData  - Global system payment data
     *
     * @return array - payment ids
     */

    public function savePayment(array $interSwitchData, array $paymentAppData)
    {
        try {

            $this->create();

            if (!$this->save($interSwitchData)) {
                throw new Exception(
                    'Cannot save payment data. InterSwitch Details: ' .
                    var_export($interSwitchData) .
                    ' PaymentApp details: ' .
                    var_export($paymentAppData), 500
                );
            }

            $paymentAppData['transaction_id'] = $this->getLastInsertID();

            $transaction_id = $this->PaymentAppModel->savePayment($paymentAppData);

            $this->id = $paymentAppData['transaction_id'];

            $this->save(array('payment_id' => $transaction_id));

            return array(
                'interSwitchId' =>  $paymentAppData['transaction_id'],
                'paymentAppId'  =>  $transaction_id
            );
        } catch(Exception $e) {
            CakeLog::write('Payments', $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Update payment data
     *
     * @param int   $paymentId - Payment id
     * @param array $update    - Update data
     *
     * @return mixed
     */
    public function updatePayment($paymentId, $update = array())
    {
        $this->create();

        $this->id = $paymentId;

        return $this->save($update);
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
        if (is_null($config['Fields']['hash']) || $config['Fields']['hash'] == '') {
            $hash  =    '';
            $hash .=    $config['Fields']['txn_ref'];
            $hash .=    $config['Fields']['product_id'];
            $hash .=    $config['Fields']['pay_item_id'];
            $hash .=    $config['Fields']['amount'];
            $hash .=    $config['Fields']['site_redirect_url'];
            $hash .=    $config['Config']['mac_key'];

            $config['Fields']['hash'] = strtoupper(Hash('sha512', $hash));
        }

        return $config['Fields']['hash'];
    }
}