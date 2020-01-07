<?php
/**
 * BonusCode Model
 *
 * Handles BonusCode Data Source Actions
 *
 * @package    BonusCodes.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class BonusCode extends AppModel
{
    /**
     * Model name
     * @var string
     */
    public $name = 'BonusCode';

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
        'code'     => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => false
        ),
        'amount'       => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'times'     => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'expires'    => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        )
    );

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = array('BonusCodesUser');

    /**
     * Find bonus code
     *
     * @param $code
     * @return array
     */
    public function findBonusCode($code)
    {
        $options['conditions'] = array(
            'BonusCode.code' => $code,
            'BonusCode.times >'     => 0,
            'BonusCode.expires >'   => gmdate('Y-m-d H:i:s')
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('first', $options);
        return $bonusCode;
    }

    /**
     * Use bonus code
     *
     * @param $id
     */
    public function useCode($id)
    {
        $options['conditions'] = array(
            'BonusCode.id' => $id
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('first', $options);
        $bonusCode['BonusCode']['times'] = $bonusCode['BonusCode']['times'] - 1;
        $this->save($bonusCode);
    }

    /**
     * Returns scaffold add fields
     *
     * @return array
     */
    public function getAdd()
    {
        return array(
            'BonusCode.code'    =>  array(
                'type'  =>  'text',
                'label' =>  __("Code")
            ),

            'BonusCode.amount'  =>  array(
                'type'  =>  'number',
                'label' =>  __("Amount")
            ),

            'BonusCode.times'  =>  array(
                'type'  =>  'number',
                'label' =>  __("Times used")
            ),

            'BonusCode.expires'  =>  $this->getFieldHtmlConfig('date', array('label' => __("Expires")))
        );
    }

    /**
     * Returns admin scaffold edit fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        return array(
            'BonusCode.code'    =>  array(
                'type'  =>  'text',
                'label' =>  __("Code")
            ),

            'BonusCode.amount'  =>  array(
                'type'  =>  'number',
                'label' =>  __("Amount")
            ),

            'BonusCode.times'  =>  array(
                'type'  =>  'number',
                'label' =>  __("Times used")
            ),

            'BonusCode.expires'  =>  $this->getFieldHtmlConfig('date', array('label' => __("Expires")))
        );
    }
}