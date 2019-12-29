<?php
/**
 * Front Payment Bonuses Controller
 *
 * Handles Payment Bonuses Actions
 *
 * @package    Payment Bonuses
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class PaymentBonusesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'PaymentBonuses';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('PaymentBonus');

    /**
     * Triggers Scaffolding
     *
     * @var
     */
    public $scaffold;

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();
    }
	
	function __setSelectBox(){
		$l = $this->PaymentBonus->PaymentBonusGroup->find('list');
		$this->set('paymentBonusGroups',$l);
	}

    /**
     * Admin Index scaffold functions
     *
     * @param array $conditions -   admin_index scaffold conditions
     * @param null  $model      -   admin_index $model name
     *
     * @return array
     */
	public function admin_index($conditions = array(), $model = null)
    {
		return parent::admin_index();
	}

    /**
     * Admin Edit scaffold functions
     *
     * @param int $id - edit item id
     *
     * @return void
     */
	public function admin_edit($id)
    {
		$this->__setSelectBox();
		parent::admin_edit($id);
	}
	
	
	function admin_add($id = null)
    {
		$this->__setSelectBox();
		parent::admin_add($id);
	}
	
	function admin_delete($id){
		parent::admin_delete($id);
	}
}