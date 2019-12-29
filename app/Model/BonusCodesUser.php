<?php
/**
 * BonusCodesUser Model
 *
 * Handles BonusCodesUser Data Source Actions
 *
 * @package    BonusCodesUser.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class BonusCodesUser extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'BonusCodesUser';

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array('BonusCode', 'User');

    /**
     * Find user bonus code
     *
     * @param $bonusCodeId
     * @param $userId
     * @return array
     */
    public function findBonusCode($bonusCodeId, $userId)
    {
        $options['conditions'] = array(
            'BonusCodesUser.bonus_code_id' => $bonusCodeId,
            'BonusCodesUser.user_id' => $userId            
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('first', $options);        
        return $bonusCode;
    }

    /**
     * Add code to user
     *
     * @param $bonusCodeId
     * @param $userId
     */
    public function addCode($bonusCodeId, $userId)
    {
        $data['BonusCodesUser']['bonus_code_id'] = $bonusCodeId;
        $data['BonusCodesUser']['user_id'] = $userId;
        $this->save($data);
    }
}