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

class Etranzacts extends PaymentAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Etranzact';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'payments_etranzact';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'payments_etranzact';

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
            'type'      => 'string',
            'length'    => null,
            'null'      => true
        )
    );

    // States: 'pending','failed','completed'
}