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

class DepositsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Deposits';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0   =>  'Deposit',
        1   =>  'PaymentBonus',
        2   =>  'PaymentBonusGroup',
        3   =>  'PaymentBonusUsage',
        4   =>  'DepositMeta',
        5   =>  'User',
        6   =>  'Withdraw',
        7   =>  'Pin'
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
        $this->Auth->allow(array('add'));
    }

    /**
     * Shows user deposits table
     *
     * @return void
     */
    public function index()
    {
        $this->layout = 'user-panel';

        $this->Deposit->locale       =   Configure::read('Config.language');
        $this->Paginator->settings  =   array($this->Deposit->name => array(
            "conditions"    =>  array(
                'Deposit.user_id'    => $this->Auth->user('id')
            ),
            "order" =>  array(
                'Deposit.date' => 'DESC'
            ),
            "limit" =>  20
        ));

        $data    =   $this->Paginator->paginate( $this->Deposit->name );

        $this->set('data', $data);
    }

    /**
     * Check bonus code and expirity
     * Set view variables
     *
     * @param $amount
     * @return bool
     */
    protected function __check_bonus($amount)
    {

        if(!isset($this->request->data['bonus_code'])) {
            return true;
        }

        $code = trim($this->request->data['bonus_code']);
        if (strlen($code) < 1) {
            return true;
        }

        $ret = $this->PaymentBonus->getBonus(null, $code);

        if ($ret == null) {
            $this->App->setMessage(__('Bonus code expired or wrong'), 'error');
            $this->redirect($this->referer());
            return false;
        }

        $userId = $this->Auth->user('id');

        $used = $this->PaymentBonusUsage->getUsedCount($ret['PaymentBonus']['id'], $userId);

        if ($used >= $ret['PaymentBonus']['max_used_count']) {
            $this->App->setMessage(__('Bonus code expired or wrong'), 'error');
            $this->redirect($this->referer());
            return false;
        }

        if (($ret['PaymentBonus']['min_amount'] > 0) && ($ret['PaymentBonus']['min_amount'] > $amount)) {
            $this->App->setMessage(__('Your amount is too low for bonus'), 'error');
            $this->redirect($this->referer());
            return false;
        }

        if (($ret['PaymentBonus']['max_amount'] > 0) && ($ret['PaymentBonus']['max_amount'] < $amount)) {
            $this->App->setMessage(__('Your amount is too high for bonus'), 'error');
            $this->redirect($this->referer());
            return false;
        }

        $this->set('DepositBonusUsed', $used);
        $this->set('DepositBonusMax', $ret['PaymentBonus']['max_used_count']);
        $this->set('DepositBonuxValitUntil', $ret['PaymentBonus']['valid_before']);

        $this->set('DepositBonusName', $ret['PaymentBonusGroup']['name']);

        $calc = $this->PaymentBonus->calculateBonus($ret, $amount);

        $this->set('DepositBonusPriemium', $calc['bonusAmount']);

        $this->Session->write('PaymentBonus', $ret);
        return $ret;
    }

    /**
     * Admin user
     *
     * @param null $userId
     */
    public function admin_user($userId = null) {
        $args = array();
        if (isset($userId)) {
            $args['conditions'] = array(
                'Deposit.user_id' => $userId
            );
        }
        $this->admin_index($args);
        $this->view = 'admin_index';
    }

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
        $status = !is_string($conditions) || $conditions == "" ? 'pending' : $conditions;
        $conditions = array('Deposit.status' => $this->Deposit->getDepositStatus($status));

        $this->request->data = array(
            $this->Deposit->name    =>  $this->request->query
        );

        // price range
        if (isset($this->request->data[$this->Deposit->name]["amount_from"]) || isset($this->request->data[$this->Deposit->name]["amount_to"])) {
             if (!is_numeric($this->request->data[$this->Deposit->name]["amount_from"])) {
                 $this->request->data[$this->Deposit->name]["amount_from"] = '0';
             }
            if (!is_numeric($this->request->data[$this->Deposit->name]["amount_to"])) {
                $this->request->data[$this->Deposit->name]["amount_to"] = '0';
            }
        }

        if (!empty($this->request->data)) {
            $conditions = array_merge(array($conditions), $this->Deposit->getSearchConditions($this->request->data));
        }

        $this->Paginator->settings  =   array(
            $this->Deposit->name => $this->Deposit->getPagination()
        );

        $this->Paginator->settings[$this->Deposit->name]["conditions"] = $conditions;

        $data = $this->Paginator->paginate( $this->Deposit->name );

        foreach ($data as $i => $row) {
            foreach ($row['User'] as $key => $value) {
                if($key == 'username' && $value == null) {
                    $row['Deposit'][$key] =  __('User Terminated');
                }
//                $data[$i]['Deposit']['user_id'] = $row['User']['username'];
            }
            $data[$i]['Deposit']["type"] = $row["Deposit"]["type"] == 1 ? __("Online") : __("Offline");
        }

        $this->set('chartsData', array(
            0   =>  array(
                'settings'  =>  array(
                    'id'            =>  0,
                    'title'         =>  __('Statuses chart'),
                    'radius'        =>  120
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label' =>  __('Completed'),
                        'count' =>  $this->Deposit->getCount(array('Deposit.status' => array(Deposit::DEPOSIT_STATUS_COMPLETED))),
                    ),
//                    1   =>  array(
//                        'label' =>  __('Pending'),
//                        'count' =>  $this->Deposit->getCount(array('Deposit.status' => array(Deposit::DEPOSIT_STATUS_PENDING))),
//                    ),
//                    2   =>  array(
//                        'label' =>  __('Cancelled'),
//                        'count' =>  $this->Deposit->getCount(array('Deposit.status' => array(Deposit::DEPOSIT_STATUS_CANCELLED))),
//                    )
                )
            ),

            1   =>  array(
                'settings'  =>  array(
                    'id'            =>  1,
                    'title'         =>  __('Amount chart')
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label' =>  '1-50' . Configure::read('Settings.currency'),
                        'count' =>  $this->Deposit->getCount(array('Deposit.amount BETWEEN ? AND ?'   => array(1, 50)))
                    ),
                    1   =>  array(
                        'label' =>  '50-150' . Configure::read('Settings.currency'),
                        'count' =>  $this->Deposit->getCount(array('Deposit.amount BETWEEN ? AND ?'   => array(50, 150)))
                    ),
                    2   =>  array(
                        'label' =>  '150-500' . Configure::read('Settings.currency'),
                        'count' =>  $this->Deposit->getCount(array('Deposit.amount BETWEEN ? AND ?'   => array(150, 500)))
                    ),
                    3   =>  array(
                        'label' =>  '500-1000' . Configure::read('Settings.currency'),
                        'count' =>  $this->Deposit->getCount(array('Deposit.amount BETWEEN ? AND ?'   => array(500, 1000)))
                    ),
                    4   =>  array(
                        'label' =>  '1000'. Configure::read('Settings.currency'). ' >',
                        'count' =>  $this->Deposit->getCount(array('Deposit.amount >= ?'              => array(1000)))
                    )
                )
            )
        ));

        $this->set('data', $data);
        $this->set('model', 'Deposit');
        $this->set('actions', $this->Deposit->getActions($this->request));
        $this->set('tabs', $this->Deposit->getTabs($this->request->params));
        $this->set('search_fields', $this->Deposit->getSearch());
    }

    public function add()
    {
        if ($this->request->header('Authorization') != "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijk5NzBmNGU3MDhiYjUyNjQzM2YzZGRl") {
            exit;
        }

        $options['conditions'] = array(
            'User.username' => $this->request->data["username"]
        );

        $this->User->contain();

        $user = $this->User->find('first', $options);

        if (empty($user)) {
            echo json_encode(array(404)); exit;
        }

        if ($deposit = $this->Deposit->addDeposit($user['User']['id'], $this->request->data["amount"], null, Deposit::DEPOSIT_TYPE_OFFLINE, "Credited", Deposit::DEPOSIT_STATUS_PENDING)) {
            $this->Deposit->setStatus($deposit["Deposit"]["id"], Deposit::DEPOSIT_STATUS_COMPLETED, true);
            echo json_encode(array("success"));
        } else {
            echo json_encode(array("failed"));
        }

        exit;
    }
    /**
     * Admin Add scaffold functions
     *
     * @param null $id - parentId
     *
     * @return void
     */
    public function admin_add($id = null)
    {
        $User = $this->User->getItem($id);

        if (empty($User)) {
            $this->Admin->setMessage(__('User does not exist!'), 'error');
            $this->redirect($this->referer(), null, true);
        }

        if ($this->request->is('post')) {
            $amount = isset($this->request->data['Deposit']['amount']) ? $this->request->data['Deposit']['amount'] : 0;

            if (!is_numeric($amount)) {
                $this->Admin->setMessage(__('Please set correct amount!'), 'error');
                $this->redirect($this->referer(), null, true);
            }

            $staffNotice = $this->Withdraw->getStaffNotice($User['User']['username'], $User['User']['id'], $this->Auth->user('username'));
            $usersNotice = $this->Deposit->getUsersNotice($User['User']['username'], $User['User']['id']);

            if ($this->Deposit->addDeposit($User['User']['id'], $amount, null, Deposit::DEPOSIT_TYPE_OFFLINE, $usersNotice, Deposit::DEPOSIT_STATUS_COMPLETED)) {
                $this->Withdraw->addWithdraw($this->Auth->user('id'), '-', $amount, Withdraw::WITHDRAW_TYPE_OFFLINE, $staffNotice, Withdraw::WITHDRAW_STATUS_COMPLETED);
                $this->Admin->setMessage(__('User %s is credited by %s %s', $User['User']['username'], $amount, Configure::read('Settings.currency')), 'success');
            } else {
                $this->Admin->setMessage(__('Please check entered amount'), 'error');
            }

            $this->redirect($this->referer(), null, true);
        }

        $this->set('user', $User);
    }

    /**
     * Admin Completed
     */
    public function admin_completed()
    {
        $this->admin_index('completed');
        $this->view = 'admin_index';
        $this->set('actions', array());
    }

    /**
     * Admin canceled
     */
    public function admin_cancelled() {
        $this->admin_index('cancelled');
        $this->view = 'admin_index';
        $this->set('actions', array());
    }

    /**
     * Admin complete
     *
     * @param $id
     */
    public function admin_complete($id) {

        $deposit = $this->Deposit->getItem($id);

        if(!$deposit) {
            $this->Admin->setMessage(__('Deposit not found!'), 'error');
            $this->redirect($this->referer());
            exit;
        }

        if($deposit['Deposit']['status'] == Deposit::DEPOSIT_STATUS_COMPLETED) {
            $this->Admin->setMessage(__('Deposit already marked as "completed"!'), 'error');
            $this->redirect($this->referer());
            exit;
        }

        $this->Deposit->setStatus($id, Deposit::DEPOSIT_STATUS_COMPLETED);

        $this->Admin->setMessage(__('Deposit request set as completed'), 'success');

        $this->redirect($this->referer());
    }

    /**
     * Admin cancel
     *
     * @param $id
     */
    public function admin_cancel($id)
    {
        $this->Deposit->setStatus($id, Deposit::DEPOSIT_STATUS_CANCELLED);
        $this->Admin->setMessage(__('Deposit request cancelled'), 'success');
        $this->redirect($this->referer());
    }

    /**
     * Deposits success message
     *
     * return @void
     */
    public function success()
    {
        $this->Admin->setMessage(__('Money transaction completed'), 'success');
    }

    /**
     * Deposits payment result
     *
     * return @void
     */
    public function result()
    {
        $this->layout = 'user-panel';

        if($this->request->is('post')) {

            $amount = isset($this->request->data['Deposit']['amount']) ? str_replace(',', '.', $this->request->data['Deposit']['amount']) : 0;
            $phone = isset($this->request->data['Deposit']['phone_number']) ? $this->request->data['Deposit']['phone_number'] : null;
            $pin = isset($this->request->data['Deposit']['pin']) ? $this->request->data['Deposit']['pin'] : '';
            if ($amount < Configure::read('Settings.minDeposit')) {
                $this->App->setMessage(__('Lowest deposit amount is ') . Configure::read('Settings.minDeposit') . Configure::read('Settings.currency'), 'error');
                $this->redirect($this->referer(), null, true);
            } else if ($amount > Configure::read('Settings.maxDeposit')) {
                $this->App->setMessage(__('Highest deposit amount is ') . Configure::read('Settings.maxDeposit') . Configure::read('Settings.currency'), 'error');
                $this->redirect($this->referer(), null, true);
            }

            $ret            = $this->__check_bonus($amount);
            $calc           = $this->PaymentBonus->calculateBonus($ret, $amount);

            if($this->Deposit->addDeposit
            (
                $this->Auth->user('id'),
                $calc['totalAmount'],
                $phone,
                Deposit::DEPOSIT_TYPE_ONLINE,
                __('Manual request by E-Pin'),
                Deposit::DEPOSIT_STATUS_PENDING,
                Deposit::Pin_Code_App,
                $pin
            )) {
                $this->Pin->setStatus($pin, Pin::Pending);
                $this->PaymentBonusUsage->commitBonus($ret, $calc, $this->Auth->user('id'));
                $this->App->setMessage(__('Deposit is accepted.'), 'success');
            }else{
                $this->App->setMessage(__('Deposit is failed.'), 'error');
            }
        }

        $this->redirect(array('plugin' => false, 'action' => 'index'), null, true);
    }

    /**
     * Preview payment deposit
     *
     * @return void
     */
    public function preview() {
        $this->layout = 'user-panel';

        $bonus_code = 0;
        $pin_code="";
        $amount = isset($this->request->data['Deposit']['amount']) ? str_replace(',', '.', $this->request->data['Deposit']['amount']) : 0;

        if (isset($this->request->data["imp1"]) && $this->request->data["imp1"] != "0") {
            $this->redirect(
                Router::url(array('language' =>  Configure::read('Config.language'), 'plugin' => 'payments', 'controller' => $this->request->data["imp1"]), true)
                . '?' .
                http_build_query($this->request->data)
            );
        }

        if (!empty($this->request->data)) {
            $pin=$this->Pin->getPinByCode($amount);
            if ($pin==null){
                $this->App->setMessage(__('There isn not Pin code'), 'error');
                $this->redirect($this->referer());
            }
            $pin=$pin['Pin'];
            if ($pin['status']==Pin::Used){
                $this->App->setMessage(__('Your pin code was already used'), 'error');
                $this->redirect($this->referer());
            }
            elseif ($pin['status']==Pin::Pending){
                $this->App->setMessage(__('Your pin code is pending'), 'error');
                $this->redirect($this->referer());
            }
            $amount=$pin['amount'];
            $pin_code=$pin['pin'];

            if ($amount < Configure::read('Settings.minDeposit')) {
                $this->App->setMessage(__('Lowest deposit amount is ') . Configure::read('Settings.minDeposit') . Configure::read('Settings.currency'), 'error');
                $this->redirect($this->referer());
            } else if ($amount > Configure::read('Settings.maxDeposit')) {
                $this->App->setMessage(__('Highest deposit amount is ') . Configure::read('Settings.maxDeposit') . Configure::read('Settings.currency'), 'error');
                $this->redirect($this->referer());
            }
        }

        if(isset($this->request->data['Deposit']['bonus_code'])) {
            $bonus_code = $this->request->data['Deposit']['bonus_code'];
            $this->__check_bonus($bonus_code);
        }

        $this->set('bonusCode', $bonus_code);
        $this->set('type', 'Manual Deposit by E-Pin');
        $this->set('amount', $amount);
        $this->set('pin', $pin_code);
    }

    /**
     * Deposits payment methods
     *
     * @return void
     */
    public function choose() { $this->layout = 'user-panel'; }

    /**
     * Displays deposit payment method
     *
     * @return void
     */
    public function deposit() {
        $this->layout = 'user-panel';

        $l = $this->PaymentBonusGroup->find('list');
        array_unshift($l, array('-1' => __('None')));
        $this->set('bonuses', $l);
    }
}