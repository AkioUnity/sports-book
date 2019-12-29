<?php
/**
 * PaymentBonusUsage Model
 *
 * Handles PaymentBonusUsage Data Source Actions
 *
 * @package    PaymentBonusUsages.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class PaymentBonusUsage extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'PaymentBonusUsage';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'                    => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'usage_time'            => array(
            'type'      => 'timestamp',
            'length'    => null,
            'null'      => true
        ),
        'user_id'               => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'payment_bonus_id'      => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'transfer_total_amount' => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => true
        ),
        'transfer_bonus'        => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => true
        ),
        'payment_bonus_title'    => array(
            'type'      => 'string',
            'length'    => 80,
            'null'      => true
        ),
        'payment_bonus_code'    => array(
            'type'      => 'string',
            'length'    => 80,
            'null'      => true
        )
    );

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
	public $belongsTo = array('User');
	
	/**
	 * 
	 * @param int $bonus_id
	 * @param int $user_id
	 * @return int count of used bonus code
	 */
	public function getUsedCount($bonus_id,$user_id)
    {
		$options['recursive'] = -1;
		$options['conditions'] = array (
				'PaymentBonusUsage.user_id' => $user_id,
				'PaymentBonusUsage.payment_bonus_id' => $bonus_id
			);
		
		return $this->find('count',$options);
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

        unset($tabs['usersadmin_add']);
        unset($tabs['usersadmin_search']);

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
        return array();
    }

    /**
     * Save log
     *
     * @param $paymentbonus
     * @param $calcamounts
     * @param $userid
     * @return bool|mixed
     */
    public function commitBonus($paymentbonus,$calcamounts,$userid){
	  if ($paymentbonus == null || !isset($paymentbonus['PaymentBonus']) ) return true;
	  $data['PaymentBonusUsage'] = array();
	  $data['PaymentBonusUsage']['user_id'] = $userid;
	  $data['PaymentBonusUsage']['payment_bonus_id'] = $paymentbonus['PaymentBonus']['id'];
	  $data['PaymentBonusUsage']['transfer_total_amount'] = $calcamounts['totalAmount'];
	  $data['PaymentBonusUsage']['transfer_bonus'] = $calcamounts['bonusAmount'];
	  $data['PaymentBonusUsage']['payment_bonus_title'] =  $paymentbonus['PaymentBonusGroup']['name'];
	  $data['PaymentBonusUsage']['payment_bonus_code'] = $paymentbonus['PaymentBonus']['bonus_code'];
	  return $this->save($data);
	}
}