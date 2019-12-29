<?php
/**
 * Virtual controller
 *
 * Handles Pages Actions
 *
 * @package    Pages
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

//App::uses('VirtualsportsAppController', 'Virtualsports.Controller');
//App::uses('String', 'Utility');

class VirtualsportsController extends VirtualsportsAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Virtualsports';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        'Virtualsports.Virtualsports'
    );



    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('index'));
    }

    function index(){
        $this->layout = 'virtual-sports';
    }



}