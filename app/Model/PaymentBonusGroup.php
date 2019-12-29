<?php
/**
 * PaymentBonusGroup Model
 *
 * Handles PaymentBonusGroup Data Source Actions
 *
 * @package    PaymentBonusesGroup.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class PaymentBonusGroup extends AppModel{

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'PaymentBonusGroup';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'name'     => array(
            'type'      => 'string',
            'length'    => 80,
            'null'      => false
        )
    );

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
	public $hasMany= array('PaymentBonus');

    /**
     * Custom display field name.
     * Display fields are used by Scaffold, in SELECT boxes' OPTION elements.
     *
     * @var string
     */
    public $displayField = 'name';

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
		
		$show_codes = array(
            'name'          => 'Edit codes',
            'controller'    => 'payment_bonuses',
            'action'        => 'admin_index',
		);
		
		array_unshift($actions, $show_codes);
		
		return $actions;
	}
}