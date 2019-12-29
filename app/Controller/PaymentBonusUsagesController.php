<?php
/**
 * Front Payment Bonus Usages Controller
 *
 * Handles Payment Bonus Usages Actions
 *
 * @package    Payment Bonus Usages
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class PaymentBonusUsagesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'PaymentBonusUsages';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('PaymentBonusUsage');

    /**
     * Called before the controller action.
     *
     * @return void
     */
	public function beforeFilter()
    {
		parent::beforeFilter();
	}
}