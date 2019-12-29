<?php
 /**
 * Short description for class
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

App::uses('AppController', 'Controller');
App::uses('PaymentAppModel', 'Payments.Model');
App::uses('Withdraw', 'Model');
App::uses('PaymentAppControllerInterface', 'Payments.Controller/Interface');
App::uses('DepositListener', 'Event');
App::uses('WithdrawListener', 'Event');

class PaymentAppController extends AppController implements PaymentAppControllerInterface
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'PaymentApp';

    /**
     * Paginate
     *
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     *
     * @var array
     */
    public $uses = array('Payments.PaymentAppModel', 'Deposit', 'Withdraw');

    /**
     * The name of the layout file to render the view inside of
     *
     * @var string
     */
    public $layout = 'user-panel';

    /**
     * Payment config
     *
     * @var array
     */
    protected $config = array();

    /**
     * Failed payment state
     */
    const STATE_FAILED = 'failed';

    /**
     * Pending payment state
     */
    const STATE_PENDING = 'pending';

    /**
     * Success payment state
     */
    const STATE_SUCCESS = 'success';

    /**
     * Called before the controller action.
     *
     * @throws Exception
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->PaymentAppModel = new PaymentAppModel();

        try {
            $this->config = $this->PaymentAppModel->getConfig();
        } catch (Exception $e) {
            $this->config = array();
        }

        // TODO: revert
        $this->Auth->allow('index', 'submitPayment', 'callback');
    }

    public function index()
    {
        $this->layout = 'user-panel';

        $this->request->data[$this->name] = $this->request->query('Deposit');
    }

    public function callback() {}

    public function result() { }

    public function submitPayment()
    {
        throw new Exception(__("Provider %s must implement submitPayment() method", $this->name), 500);
    }

    public function submitWithdraw()
    {
        throw new Exception(__("Provider %s must implement submitWithdraw() method", $this->name), 500);
    }
}