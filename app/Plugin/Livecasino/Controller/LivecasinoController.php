<?php
/**
 * Live casino controller
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

//App::uses('LivecasinoAppController', 'Livecasino.Controller');
//App::uses('String', 'Utility');

class LivecasinoController extends LivecasinoAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Livecasino';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        'Livecasino.Livecasino'
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
        $this->layout = 'live-casino';
    }



}