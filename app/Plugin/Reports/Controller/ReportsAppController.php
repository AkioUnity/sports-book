<?php
/**
 * Front Reports App Controller
 *
 * Handles Reports App Actions
 *
 * @package    Reports.Controller
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class ReportsAppController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'ReportsApp';

    /**
     * Array containing the names of components this controller uses.
     *
     * @var $components array
     */
    public $components = array(
        0   =>  'Reports.Reports'
    );

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Reports.ReportApp', 'User', 'Ticket', 'Deposit', 'Withdraw');
}
