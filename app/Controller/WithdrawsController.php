<?php
/**
 * Front Withdraws Controller
 *
 * Handles Withdraws Actions
 *
 * @package    Withdraws
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class WithdrawsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Withdraws';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Pin','Withdraw', 'Deposit', 'Payments.PaymentAppModel');

    /**
     * @var array
     */
    public $actsAs = array('Containable');

    /**
     * User withdraw action
     *
     * return @void
     */
    public function index()
    {
        $this->layout = 'user-panel';

        if (!Configure::read('Settings.withdraws')) {
            $this->redirect('/');
        }
        
        $userId         = $this->Auth->user('id');
        $userWithdraws  = $this->Withdraw->getUserWithdraws($userId);
        $hasPending     = array_map(function($userWithdraw){
            if($userWithdraw['Withdraw']['status'] == Withdraw::WITHDRAW_STATUS_PENDING) {
                return true;
            }
            return false;
        }, $userWithdraws);

        $amount = isset($this->request->data['Withdraw']['amount']) ? str_replace(',', '.', $this->request->data['Withdraw']['amount']) : 0;
//        print_r($this->request->data['Withdraw']);
        $account = isset($this->request->data['Withdraw']['account']) ? $this->request->data['Withdraw']['account'] : "";
        $bank = isset($this->request->data['Withdraw']['bank']) ? $this->request->data['Withdraw']['bank'] : null;
        $accountHolder = isset($this->request->data['Withdraw']['account_holder']) ? $this->request->data['Withdraw']['account_holder'] : null;
        $cardNo = isset($this->request->data['Withdraw']['card_no']) ? $this->request->data['Withdraw']['card_no'] : null;
        $notes = isset($this->request->data['Withdraw']['notes']) ? $this->request->data['Withdraw']['notes'] : null;

        if (!empty($this->request->data)) {
            if ($amount < Configure::read('Settings.minWithdraw')) {
                $this->App->setMessage(__('Lowest amount for cashout is ') . Configure::read('Settings.minWithdraw') . Configure::read('Settings.currency'), 'error');
            } else if ($amount > Configure::read('Settings.maxWithdraw')) {
                $this->App->setMessage(__('Highest amount for cashout is ') . Configure::read('Settings.maxWithdraw') . Configure::read('Settings.currency'), 'error');
            } else if ($amount > $this->Session->read('Auth.User.balance')) {
                $this->App->setMessage(__('Not enough money for this cashout transaction'), 'error');
            } elseif (in_array(true, $hasPending)) {
                $this->App->setMessage(__('Previous withdrawal transaction is not completed'), 'error');
            } else {
                if (in_array('submit', $this->request->pass)) {
                    $this->view = "index-submit";
                } else {
                    $data=$this->request->data['Withdraw'];
//                    $this->log($data);
                    $desc=__('Manual request');
                    $message=__("Your withdrawal request for ". $amount . Configure::read('Settings.currency') ." is accepted.");
                    if ($data['payment_provider']=='Pin Sale'){
                        $bank='E-Pin';
                        $cardNo=$this->random_pin();
                        $this->Pin->addPinNew(
                            $cardNo,
                            $userId,
                            $amount,
                            Pin::Created,
                            'Pin code was created for Withdraw'
                        );
                        $desc='E-Pin code withdraw';
                        $message=$message.' Your Pin code is '.$cardNo;
                    }
                    if ($this->Withdraw->addWithdrawNew(
                        $userId,
                        $amount,
                        $bank,
                        $accountHolder,
                        $account,
                        $cardNo,
                        $notes,
                        Withdraw::WITHDRAW_TYPE_ONLINE,
                        $desc,
                        Withdraw::WITHDRAW_STATUS_PENDING
                    ))
                    {
                        $this->App->setMessage($message, 'success');
                    }
                }
            }
        }

        $providers = array();

        foreach ($this->PaymentAppModel->getAvailableProviders() AS $provider) {
            $providers[$provider["value"]] = $provider["title"];
        }

        $this->set('paymentProviders', array("Manual" => "Manual","Pin Sale" => "Pin Sale"));
        $this->set('data', $userWithdraws);
    }

    public function random_pin(){
        $r='';
        for ($i = 0; $i<9; $i++)
        {
            $r .= mt_rand(0,9);
        }
        return $r;
    }

    /**
     * Users settings panel
     */
    public function settings() {
        if (!empty($this->request->data)) {
            $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
            unset($this->request->data['User']['username']);
            unset($this->request->data['User']['first_name']);
            unset($this->request->data['User']['last_name']);
            unset($this->request->data['User']['date_of_birth']);
            if ($this->Withdraw->User->save($this->request->data)) {
                $this->App->setMessage(__('Bank information updated.', true), 'success');
            }
        }

        $options['fields'] = array(
            'User.bank_name',
            'User.account_number'
        );

        $user = $this->Withdraw->User->getItem($this->Auth->user('id'));
        $this->set('user', $user['User']);
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
        $conditions = array('Withdraw.status' => $this->Withdraw->getWithdrawStatus($status));

        $this->request->data = array(
            $this->Withdraw->name    =>  $this->request->query
        );

        // price range
        if (isset($this->request->data[$this->Withdraw->name]["amount_from"]) || isset($this->request->data[$this->Withdraw->name]["amount_to"])) {
            if (!is_numeric($this->request->data[$this->Withdraw->name]["amount_from"])) {
                $this->request->data[$this->Withdraw->name]["amount_from"] = '0';
            }
            if (!is_numeric($this->request->data[$this->Withdraw->name]["amount_to"])) {
                $this->request->data[$this->Withdraw->name]["amount_to"] = '0';
            }
        }

        if (!empty($this->request->data)) {
            $conditions = array_merge(array($conditions), $this->Withdraw->getSearchConditions($this->request->data));
        }

        $this->Paginator->settings  =   array(
            $this->Withdraw->name => $this->Withdraw->getPagination()
        );

        $this->Paginator->settings[$this->Withdraw->name]["conditions"] = $conditions;

        $data = $this->Paginator->paginate( $this->Withdraw->name );

        foreach ($data as $i => $row) {
            foreach ($row['User'] as $key => $value) {
                if($key == 'username' && $value == null) {
                    $row['Withdraw'][$key] =  __('User Terminated');
                }
//                $data[$i]['Withdraw']['user_id'] = $row['User']['username'];
            }
            $data[$i]['Withdraw']["type"] = $row["Withdraw"]["type"] == 1 ? __("Online") : __("Offline");
        }

        $chartsData = array(
            0   =>  array(
                'settings'  =>  array(
                    'id'            =>  0,
                    'title'         =>  __('Statuses chart')
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label' =>  __('Completed'),
                        'count' =>  $this->Withdraw->getCount(array('Withdraw.status' => array(Withdraw::WITHDRAW_STATUS_COMPLETED)))
                    ),
                    1   =>  array(
                        'label' =>  __('Pending'),
                        'count' =>  $this->Withdraw->getCount(array('Withdraw.status' => array(Withdraw::WITHDRAW_STATUS_PENDING)))
                    ),
                    2   =>  array(
                        'label' =>  __('Cancelled'),
                        'count' =>  $this->Withdraw->getCount(array('Withdraw.status' => array(Withdraw::WITHDRAW_STATUS_CANCELLED)))
                    )
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
                        'count' =>  $this->Withdraw->getCount(array('Withdraw.amount BETWEEN ? AND ?'   => array(1, 50)))
                    ),
                    1   =>  array(
                        'label' =>  '50-150' . Configure::read('Settings.currency'),
                        'count' =>  $this->Withdraw->getCount(array('Withdraw.amount BETWEEN ? AND ?'   => array(50, 150)))
                    ),
                    2   =>  array(
                        'label' =>  '150-500' . Configure::read('Settings.currency'),
                        'count' =>  $this->Withdraw->getCount(array('Withdraw.amount BETWEEN ? AND ?'   => array(150, 500)))
                    ),
                    3   =>  array(
                        'label' =>  '500-1000' . Configure::read('Settings.currency'),
                        'count' =>  $this->Withdraw->getCount(array('Withdraw.amount BETWEEN ? AND ?'   => array(500, 1000)))
                    ),
                    4   =>  array(
                        'label' =>  '1000'. Configure::read('Settings.currency'). ' >',
                        'count' =>  $this->Withdraw->getCount(array('Withdraw.amount >= ?'              => array(1000)))
                    )
                )
            )
        );

        $this->set('chartsData', $chartsData);

        $this->set('data', $data);
        $this->set('model', 'Withdraw');
        $this->set('actions', $this->Withdraw->getActions($this->request));
        $this->set('tabs', $this->Withdraw->getTabs($this->request->params));
        $this->set('search_fields', $this->Withdraw->getSearch());

        return $data;
    }
    
    public function admin_completed() {
        $this->admin_index('completed');
        $this->view = 'admin_index';
        $this->set('actions', array());
    }
    public function admin_cancelled() {
        $this->admin_index('cancelled');
        $this->view = 'admin_index';
        $this->set('actions', array());
    }

    /**
     * Complete
     *
     * @param $id
     */
    public function admin_complete($id) {
        if (!empty($this->request->data)) {
            $description = isset($this->request->data["Withdraw"]["message"]) ? $this->request->data["Withdraw"]["message"] : null;
            $this->Withdraw->setStatus($id, Withdraw::WITHDRAW_STATUS_COMPLETED, $description);
            $this->Admin->setMessage(__('Withdraw request set as completed'), 'success');
            $this->redirect(array('admin' => true, 'plugin' => null, 'controller' => 'withdraws', 'action' => 'index'), 302, true);
        }

        $this->set('id', $id);
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

        if(empty($User)) {
            $this->Admin->setMessage(__('User does not exist!'), 'error');
            $this->redirect($this->referer(), null, true);
        }

        if($this->request->is('post') && isset($this->request->data['Withdraw']['amount'])) {
            $amount = $this->request->data['Withdraw']['amount'];

            if(!is_numeric($amount)) {
                $this->Admin->setMessage(__('Amount should be positive'), 'error');
                $this->redirect($this->referer(), null, true);
            }

            $usersNotice = $this->Withdraw->getUsersNotice($User['User']['username'], $User['User']['id']);
            $staffNotice = $this->Deposit->getStaffNotice($this->Auth->user('username'), $this->Auth->user('id'), $this->Auth->user('username'));

            if($this->Withdraw->addWithdraw($User['User']['id'], '-', $amount, Withdraw::WITHDRAW_TYPE_OFFLINE, $usersNotice, Withdraw::WITHDRAW_STATUS_COMPLETED)) {
                $this->Deposit->addDeposit($this->Auth->user('id'), $amount, null, Deposit::DEPOSIT_TYPE_OFFLINE, $staffNotice, Deposit::DEPOSIT_STATUS_COMPLETED);
                $this->Admin->setMessage(__('User %s is charged by %s %s', $this->Auth->user('username'), $amount, Configure::read('Settings.currency')), 'success');
            }else{
                $this->Admin->setMessage(__('Please check entered amount', true), 'error');
            }

            $this->redirect($this->referer(), null, true);
        }

        $this->set('userName', $User['User']['username']);
        $this->set('user', $User);
    }

    public function admin_cancel($id)
    {
        $this->Withdraw->setStatus($id, Withdraw::WITHDRAW_STATUS_CANCELLED);
        $withdraw = $this->Withdraw->getItem($id);
        $amount = $withdraw['Withdraw']['amount'];
        $userId = $withdraw['Withdraw']['user_id'];
        $this->Withdraw->User->addFunds($userId, $amount);
        $this->Admin->setMessage(__('Withdraw request cancelled'), 'success');
        $this->redirect($this->referer());
    }
    
    public function admin_user($userId = null)
    {
        $args = array();
        if (isset($userId)) {
            $args['conditions'] = array(
                'Withdraw.user_id' => $userId
            );
        }
        $this->admin_index($args);
        $this->view = 'admin_index';
    }

    /**
     * Admin view scaffold functions
     *
     * @param int $id - view id
     *
     * @return void
     */
    public function admin_view($id = -1)
    {
    	$model = $this->__getModel();
    	$this->{$model}->locale = Configure::read('Config.language');
    	$data = $this->{$model}->getView($id);
    	$aData =$data;
    	if (!empty($data)) {
    		$data = $this->{$model}->getAdminIndexSchema($data);
    		$this->set('fields', $data);
    	} else {
    		$this->Admin->setMessage(__('can\'t find', true));
    	}
    	
    	$user = $this->Withdraw->User->getItem($aData['Withdraw']['user_id']);
        $this->set('user', $user['User']);
        $this->render('/Withdraws/admin_view');
    }

    public function admin_manage_epin(){
        if($this->request->is('post') && isset($this->request->data['Withdraw']['amount'])) {
            $amount = $this->request->data['Withdraw']['amount'];

            if(!is_numeric($amount)) {
                $this->Admin->setMessage(__('Amount should be positive'), 'error');
                $this->redirect($this->referer(), null, true);
            }
            if ($amount > $this->Session->read('Auth.User.balance')) {
                $this->Admin->setMessage(__('Not enough money in balance'), 'error');
                $this->redirect($this->referer(), null, true);
            }

            $cardNo=$this->random_pin();
            $message='Pin code was created by '.$this->Auth->user('username');
            $this->Pin->addPinNew(
                $cardNo,
                $this->Auth->user('id'),
                $amount,
                Pin::Created,
                $message
            );
            $this->Admin->setMessage($message.'  amount:', $amount, Configure::read('Settings.currency'), 'success');
            $this->redirect($this->referer(), null, true);
        }
        $this->Paginator->settings  =   array(
            $this->Pin->name => $this->Pin->getPagination('Created')
        );
        $data = $this->Paginator->paginate( $this->Pin->name );

        $this->set('data', $data);
//        $this->set('model', 'Withdraw');
//        $this->set('actions', $this->Withdraw->getActions($this->request));
        return $data;
    }

    public function admin_used_epin(){
        $this->Paginator->settings  =   array(
            $this->Pin->name => $this->Pin->getPagination('Used')
        );
        $data = $this->Paginator->paginate( $this->Pin->name );

        $this->set('data', $data);
        $this->set('model', 'Pin');
//        $this->set('actions', $this->Withdraw->getActions($this->request));
        return $data;
    }
}