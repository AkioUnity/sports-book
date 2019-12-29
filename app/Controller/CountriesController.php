<?php
/**
 * Front Countries Controller
 *
 * Handles Countries Actions
 *
 * @package    Countries.Controller
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class CountriesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Countries';

    /**
     * Additional models
     *
     * @var array
     */
    public $uses = array(
        0   =>  'Country',
        1   =>  'Sport'
    );

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        0   =>  'Security'
    );

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->Auth->allow('getCountriesMenu', 'index');
        parent::beforeFilter();
    }

    /**
     * Returns countries list for menu
     */
    public function getCountriesMenu()
    {
        if (!$this->request->is('ajax')) {
//            $this->Security->blackHole($this, 'You are not authorized to process this request!');
        }

        if(isset($this->request->query['sportId']) && is_numeric($this->request->query['sportId'])) {
            $sportId = (int) $this->request->query['sportId'];
        }else{
            $sportId = null;
        }

        $this->Sport->contain();

        $this->set('sportId', $sportId);
        $this->set('sportData', $this->Sport->getSport($sportId));
        $this->set('CountriesMenu', $this->Country->getCountries(Country::STATE_COUNTRY_ACTIVE, $sportId));
    }
}