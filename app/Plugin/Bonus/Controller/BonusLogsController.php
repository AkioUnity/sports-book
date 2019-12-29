<?php
 /**
 * Handles Betting Bonus
 *
 * Long description for class (if any)...
 *
 * @package    Payments
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('BonusAppController', 'Bonus.Controller');
App::uses('EgoPaySci', 'Payments.Lib/EgoPay');

class BonusLogsController extends BonusAppController
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'BettingLogs';

    /**
     * Component
     *
     * @var array
     */
//    public $components = array();

    /**
     * Models
     *
     * @var array
     */
    public $uses = array('Payments.BonusLog');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * Index action
     */
    public function index() {}
}