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

App::uses('BonusAppModel', 'Bonus.Model');

class BettingBonus extends BonusAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'BettingBonus';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'bonus_betting';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'bonus_betting';

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

    public function getPayment(array $paymentIds)
    {
        return $this->find('first', array(
            'conditions'    =>  array(
                $this->name . '.id' =>  $paymentIds[$this->name],
            )
        ));
    }
}