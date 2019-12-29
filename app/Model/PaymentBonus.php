<?php
/**
 * PaymentBonus Model
 *
 * Handles PaymentBonus Data Source Actions
 *
 * @package    PaymentBonuses.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class PaymentBonus extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'PaymentBonus';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'                        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'bonus_code'                => array(
            'type'      => 'string',
            'length'    => 40,
            'null'      => true
        ),
        'bonus_multiplier'          => array(
            'type'      => 'double',
            'length'    => null,
            'null'      => true
        ),
        'max_used_count'            => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'min_amount'                => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => true
        ),
        'max_amount'                => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => true
        ),
        'max_bonus'                 => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => false
        ),
        'valid_after'               => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => true
        ),
        'valid_before'              => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => true
        ),
        'payment_bonus_group_id'    => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        )
    );

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array('PaymentBonusGroup');

    /**
     * Returns model name
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get bonus code which is valid
     *
     * @param $bonusGroup
     * @param $bonusCode
     * @return array
     */
    public function getBonus($bonusGroup, $bonusCode) {
        $options['conditions'] = array(
            'PaymentBonus.bonus_code' => $bonusCode,
            'PaymentBonus.valid_before > NOW()',
            'PaymentBonus.valid_after  < NOW()',
        );

        return $this->find('first', $options);
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
        unset($tabs['payment_bonusesadmin_search']);
        foreach ($tabs as $key => &$value) {
            if ($value['url'] != '#') {
                $value['url'][] = $params['pass'][0];
            }
        }

        return $tabs;
    }

    /**
     * Calculate bonus from given amount
     * @bug: maybe someone knows better place for this function (in traint)
     * @warning: rounds up
     * @see http://php.net/round
     * @param $bonuscodeObject array bonus stucture
     * @param $amount amount to deposit
     * @return double bonus amount
     */
    public function calculateBonus($bonuscodeObject, $amount) {
        if ($bonuscodeObject == null or !isset($bonuscodeObject['PaymentBonus'])) {
            $ret['totalAmount'] = $amount;
            $ret['bonusAmount'] = 0;
            return $ret;
        }
        $ret = array();
        $bonus = round($amount * $bonuscodeObject['PaymentBonus']['bonus_multiplier'] - $amount, 2);
        if ($bonuscodeObject['PaymentBonus']['max_bonus'] > 0) {
            if ($bonuscodeObject['PaymentBonus']['max_bonus'] < $bonus) {
                $bonus = $bonuscodeObject['PaymentBonus']['max_bonus'];
            }
        }
        $ret['totalAmount'] = round($bonus + $amount, 2);
        $ret['bonusAmount'] = $bonus;
        //apply bonus code limits        
        return $ret;
    }
}