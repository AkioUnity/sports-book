<?php
/**
 * Handles Deposits
 *
 * Handles Deposits Actions
 *
 * @package    Deposits
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class NotificationsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Notifications';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0   =>  'Notification'
    );

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array(
        0   =>  'Paginator'
    );

    /**
     * Components
     *
     * @var array
     */
    public $components = array();

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
     * Shows user deposits table
     *
     * @return void
     */
    public function index()
    {
        $this->layout = 'user-panel';

        $this->Notification->locale =   Configure::read('Config.language');
        $this->Paginator->settings  =   array($this->Notification->name => array(
            "conditions"    =>  array(
                'Notification.user_id'    => $this->Auth->user('id')
            ),
            "order" =>  array(
                'Notification.date' => 'DESC'
            ),
            "limit" =>  20
        ));

        $data = $this->Paginator->paginate( $this->Notification->name );

        $this->set('data', $data);
    }
}