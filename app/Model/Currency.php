<?php
/**
 * Currency Model
 *
 * Handles Currency Data Source Actions
 *
 * @package    Currencies.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Currency extends AppModel {
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Currency';

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
            'length'    => 20,
            'null'      => false
        ),
        'code'       => array(
            'type'      => 'string',
            'length'    => 20,
            'null'      => false
        ),
        'rate'     => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => false
        )
    );

    /**
     * Get code
     *
     * @param $id
     * @return mixed
     */
    public function getCode($id)
    {
        $options['conditions'] = array(
            'Currency.id' => $id
        );
        $currency = $this->find('first', $options);
        return $currency['Currency']['code'];
    }

    /**
     * Returns list
     *
     * @return array
     */
    public function getList()
    {
        $list = $this->find('list');
        return $list;
    }

    /**
     * Returns codes list
     *
     * @return array
     */
    public function getCodesList()
    {
        $options['fields'] = array(
            'Currency.id',
            'Currency.code'
        );
        $list = $this->find('list', $options);
        return $list;
    }

    /**
     * Returns admin scaffold add fields
     *
     * @return array
     */
    public function getAdd()
    {
        return array(
            'Currency.name' =>  array('type' => 'text', 'label' => __("Name")),
            'Currency.code' =>  array('type' => 'text', 'label' => __("Code")),
            'Currency.rate' =>  array('type' => 'text', 'label' => __("Rate"))
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
            'Currency.name' =>  array('type' => 'text', 'label' => __("Name")),
            'Currency.code' =>  array('type' => 'text', 'label' => __("Code")),
            'Currency.rate' =>  array('type' => 'text', 'label' => __("Rate"))
        );
    }
}