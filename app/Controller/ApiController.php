<?php
/**
 * Handles API
 *
 * Handles API Actions
 *
 * @package    API
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');

class ApiController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Api';

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        'RequestHandler',
        'Rest.Rest' => array(
            'catchredir' => true, // Recommended unless you implement something yourself
            'debug' => 0,
            'actions' => array(
                'index' => array(
                    'extract' => array('index' => array('result')),
                )
            )
        ),
        'Auth'  =>  array(
            'authorize'         =>  'controller',
            'authenticate'      =>  array(
                'Basic'
            )
        )
    );

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter () {
        // Is user logged in
        if (!$this->Auth->user()) {
            // Try to login user via REST

            if ($this->Rest->isActive()) {
                $this->Auth->autoRedirect = false;

                $credentials = $this->Rest->credentials();

                if(!isset($credentials['username']) || !isset($credentials['password'])) {
                    $msg = sprintf('Missing API credentials.');
                    return $this->Rest->abort(array('status' => '403', 'error' => $msg));
                }

                $_SERVER['PHP_AUTH_USER']   = $credentials['username'];
                $_SERVER['PHP_AUTH_PW']     = $credentials['password'];

                if (!$this->Auth->login()) {
                    $msg = sprintf('Unable to log you in with the supplied credentials. ');
                    return $this->Rest->abort(array('status' => '403', 'error' => $msg));
                }
            }
        }

        parent::beforeFilter();
    }

    /**
     * Uses list
     *
     * @var array
     */
    public $uses = array(
        0   =>  'User',
        1   =>  'Api'
    );

    /**
     * Api / index
     */
    public function index() {
        /**
         * User Group Id
         */
        $foreign_key = $this->Session->read('Auth.User.group_id');

        /**
         * API Credentials
         */
        $credentials = $this->Rest->credentials();

        /**
         * Controller Name
         */
        $controllerName = Inflector::pluralize($credentials['class']) . "Controller";

        /**
         * User not allowed
         */
        if(!$this->Acl->check(array('model' => 'Group', 'foreign_key' => $foreign_key), $credentials['class'] . '/' . $credentials['action'])) {
            $msg = sprintf('You are not authorized to access that location.');
            return $this->Rest->abort(array('status' => '403', 'error' => $msg));
        }

        /**
         * Load Controller
         */
        App::import("Controller", $credentials['class']);

        /**
         * Controller instance
         */
        $instance = new $controllerName;

        /**
         * Api Action
         */
        $action = $credentials['action'];

        /**
         * Call method and get result
         */
        $result = $instance->$action();

        /**
         * Sets result
         */
        $this->set(compact('result'));
    }

    /**
     * Shortcut so you can check in your Controllers wether
     * REST Component is currently active.
     *
     * Use it in your ->flash() methods
     * to forward errors to REST with e.g. $this->Rest->error()
     *
     * @return boolean
     */
    protected function _isRest() {
        return !empty($this->Rest) && is_object($this->Rest) && $this->Rest->isActive();
    }
}