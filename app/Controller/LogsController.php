<?php
/**
 * Front Logs Controller
 *
 * Handles Logs Actions
 *
 * @package    Logs
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class LogsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Logs';

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
        parent::admin_index(array('order' => 'Log.id DESC'));
    }
}