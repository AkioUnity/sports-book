<?php
/**
 * Front Users Controller
 *
 * Handles Users Actions
 *
 * @package    Events
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('VerifyRecaptcha', 'Lib/Recaptcha');

class UsersController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Users';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0   =>  'User',
        1   =>  'BonusCode',
        2   =>  'BonusCodesUser',
        3   =>  'PaymentBonusUsage',
        4   =>  'Bet',
        5   =>  'Ticket',
        6   =>  'Deposit',
        7   =>  'Withdraw',
        8   =>  'RuncpaModel'
    );

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array();

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        0   =>  'RequestHandler',
        1   =>  'Email'
    );


    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('login', 'admin_login', 'admin_logout', 'logout', 'disable', 'docs'/*, 'admin_statistics'*/));
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
        $user       =   $this->Auth->user();

        switch ($user["group_id"]) {
            case Group::ADMINISTRATOR_GROUP:
                $conditions = array('User.group_id' => Group::USER_GROUP);
                break;
            case Group::BRANCH_GROUP:
                $agent_id   =   isset($this->request->params["pass"][0]) ? $this->request->params["pass"][0] : null;
                if (is_null($agent_id)) {
                    $agents = $this->User->find('all', array("conditions" => array('User.group_id' => Group::AGENT_GROUP, 'User.referal_id' => $user["id"])));
                } else {
                    $agents = $this->User->find('all', array("conditions" => array('User.group_id' => Group::AGENT_GROUP, 'User.id' => $agent_id)));
                }
                if (is_array($agents) && !empty($agents)) {
                    $ids = array_map(function($agent){ return $agent["User"]["id"]; }, $agents);
                    $conditions = array('User.group_id' => Group::USER_GROUP, 'User.referal_id' => $ids);
                }
                break;
            case Group::AGENT_GROUP:
                $conditions = array('User.group_id' => Group::USER_GROUP, 'User.referal_id' => $user["id"]);
                break;
            default:
                $conditions = array('User.id' => $user["id"]);
                break;
        }

        $data = parent::admin_index($conditions);

        foreach ($data AS $item => $value) {
            $data[$item]["User"]["username"] = sprintf('<a href="/eng/admin/users/statistics/%d">%s</a>', $value["User"]["id"], $value["User"]["username"]);
        }

        $this->set('data', $data);
    }

    public function admin_statistics($id)
    {
        $User                   =   $this->User->getItem($id);

        if (empty($User)) {
            $this->Admin->setMessage(__('User does not exist!'), 'error');
            $this->redirect($this->referer(), null, true);
        }

        $ticketModel            =   $this->Ticket;
        $registrationDate       =   $User["User"]["registration_date"];
        $totalTicketsPlaced     =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $id)));
        $totalTicketsWon        =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $id, 'Ticket.status' => Ticket::TICKET_STATUS_WON)));
        $totalTicketsLost       =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $id, 'Ticket.status' => Ticket::TICKET_STATUS_LOST)));
        $totalTicketsPending    =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $id, 'Ticket.status' => Ticket::TICKET_STATUS_PENDING)));
        $totalTicketsCancelled  =   $ticketModel->find('count', array('conditions' => array('Ticket.user_id' => $id, 'Ticket.status' => Ticket::TICKET_STATUS_CANCELLED)));

        $depositModel           =   $this->Deposit;
        $withdrawModel          =   $this->Withdraw;
        $userName               =   $User["User"]["username"];
        $userBalance            =   $User["User"]["balance"];
        $userLastDeposit        =   $depositModel->find('first', array('conditions' => array('Deposit.user_id' => $id, "Deposit.status" => Deposit::DEPOSIT_STATUS_COMPLETED), 'order'=>'Deposit.id DESC'));
        $userAverageDeposit     =   $depositModel->find('first', array('conditions' => array('Deposit.user_id' => $id, "Deposit.status" => Deposit::DEPOSIT_STATUS_COMPLETED), 'fields'=> array("AVG(Deposit.amount) AS AverageDeposit")));
        $userLastWithdraw       =   $withdrawModel->find('first', array('conditions' => array('Withdraw.user_id' => $id, "Withdraw.status" => Withdraw::WITHDRAW_STATUS_COMPLETED), 'order'=>'Withdraw.id DESC'));
        $userAverageWithdraw    =   $withdrawModel->find('first', array('conditions' => array('Withdraw.user_id' => $id, "Withdraw.status" => Withdraw::WITHDRAW_STATUS_COMPLETED), 'fields'=> array("AVG(Withdraw.amount) AS AverageWithdraw")));

        $this->Paginator->settings = array('Ticket' => array(
            'contain'   =>  array('User'),
            'order'=>'Ticket.id DESC',
            'conditions' => array('Ticket.user_id' => $id),
            'limit' => 10
        ));

        $this->set('registration_date', $registrationDate);
        $this->set('total_tickets_placed', $totalTicketsPlaced);
        $this->set('total_tickets_won', $totalTicketsWon);
        $this->set('total_tickets_lost', $totalTicketsLost);
        $this->set('total_tickets_pending', $totalTicketsPending);
        $this->set('total_tickets_cancelled', $totalTicketsCancelled);
        $this->set('user_balance', $userBalance);
        $this->set('user_name', $userName);
        $this->set('user_last_deposit', $userLastDeposit);
        $this->set('user_average_deposit', $userAverageDeposit);
        $this->set('user_last_withdraw', $userLastWithdraw);
        $this->set('user_average_withdraw', $userAverageWithdraw);

        $this->set('data', $this->Paginator->paginate($ticketModel));
        $this->set('actions', array(
            0   =>  array(
                "name"          =>  "View",
                "plugin"        =>  null,
                "controller"    =>  "tickets",
                "action"        =>  "admin_view",
                "class"         =>  "btn btn-mini"
            )
        ));
    }

    /**
     * Index action
     *
     * @return void
     */
    public function index() { }

    /**
     * User registration
     *
     * @return void
     */
    public function register()
    {
        if (Configure::read('Settings.registration') != 1) {
            $this->redirect('/');
            exit;
        }

        $this->layout = 'user-tools';
        if ($this->theme == "Redesign") {
            $this->layout = 'inner';
        }

        if (!empty($this->request->data))
        {
            $this->request->data['User']['confirmation_code']   = $this->Utils->getRandomString(20);

            if ($this->request->data['User']['password'] != $this->request->data['User']['password_confirm']) {
                $this->User->invalidate('password_confirm');
            }

            if (!VerifyRecaptcha::verify($this->request->data["g-recaptcha-response"])->success) {
                $this->User->invalidate('recaptcha');
            }

            if(isset($this->request->data['User']['date_of_birth']) && is_array($this->request->data['User']['date_of_birth'])) {
                $this->request->data['User']['date_of_birth'] = implode('-', array_reverse($this->request->data['User']['date_of_birth']));
            }

            $this->User->set($this->data);

            if ($this->User->validates()) {
                $this->request->data['User']['ip']          = $this->RequestHandler->getClientIP();
                $this->request->data['User']['group_id']    = Group::USER_GROUP;
                $this->request->data['User']['password']    = Security::hash($this->request->data['User']['password'], null, true);
                $this->request->data['User']['balance']     = 0;

                $this->request->data['User']['time_zone']   = '2.0';
                $this->request->data['User']['day_stake_limit']   = '0';
                $this->request->data['User']['operator_signature']   = '';
                $this->request->data['User']['last_visit']   = gmdate('Y-m-d H:i:s');
                $this->request->data['User']['registration_date']   = gmdate('Y-m-d H:i:s');

                $this->request->data['User']['bank_name'] = isset($this->request->data['User']['bank_name']) ? $this->request->data['User']['bank_name'] : "-";
                $this->request->data['User']['account_number'] = isset($this->request->data['User']['account_number']) ? $this->request->data['User']['account_number'] : "";
                $this->request->data['User']['bank_code'] = isset($this->request->data['User']['bank_code']) ? $this->request->data['User']['bank_code'] : "";
                $this->request->data['User']['referal_id'] = isset($this->request->data['User']['referal_id']) ? $this->request->data['User']['referal_id'] : 0;

                $runCpaTrackId = RunCpa::getInstance()->getTrackId();

                $user = $this->User->register($this->request->data);

                if ($runCpaTrackId) {
                    RunCpa::getInstance()->generateConversion("cpl54939");
                    $this->RuncpaModel->store($user["User"]["id"], $runCpaTrackId);
                }

                if ($user)
                {
                    $url = Router::url(array('language' => Configure::read('Config.language'), 'controller' => 'users', 'action' => 'confirm', 'code' => $this->request->data['User']['confirmation_code']), true);
                    $link = '<a href="' . $url . '">' . $url . '</a>';
                    $vars = array('link' => $link, 'first_name' => $this->request->data['User']['first_name'], 'last_name' => $this->request->data['User']['last_name']);
                    $this->Email->sendMail('confirmation', $this->request->data['User']['email'], $vars);
                }
                $this->App->setMessage(__('Please check your given email inbox and confirmed account.'), 'success');
                $this->redirect(array('language' =>  Configure::read('Config.language'), 'plugin' => false, 'controller' => 'users', 'action' => 'login', 'confirmation'));

                exit;
            }
        }

        $this->set('referral_id', !isset($this->request->data['User']['referal_id']) ? $this->User->findReferralId(CakeSession::read('referral')) : $this->request->data['User']['referal_id']);
        $this->set('countries', $this->User->getCountriesList());
        $this->set('questions', $this->User->getQuestions());
    }

    /**
     * User registration confirmation
     *
     * @return void
     */
    public function confirm()
    {
        if (isset($this->params['named']['code'])) {

            $code = $this->params['named']['code'];
            $options['conditions'] = array(
                'User.confirmation_code' => $code
            );
            $this->User->contain();
            $user = $this->User->find('first', $options);

            if (isset($user['User']['confirmation_code']) && $user['User']['confirmation_code'] != '') {
                $user['User']['confirmation_code'] = '';
                $user['User']['status'] = '1';

                $this->User->save($user, false);

                //send mail            
                $url = Router::url(array('language' => Configure::read('Config.language'), 'controller' => 'users', 'action' => 'login'), true);
                $link = '<a href="' . $url . '">' . $url . '</a>';
                $user['User']['link'] = $link;
                $this->Email->sendMail('welcome', $user['User']['email'], $user['User']);

                $this->redirect(array('language' =>  Configure::read('Config.language'), 'plugin' => false, 'controller' => 'users', 'action' => 'login', 'confirmed'));
            }
        }
        $this->redirect(array('language' =>  Configure::read('Config.language'), 'plugin' => false, 'controller' => 'users', 'action' => 'login', 'invalid_code'));
    }

    /**
     * User password confirmation
     */
    public function password()
    {
        if (!empty($this->request->data)) {
            if ($this->request->data['User']['new_password'] == $this->request->data['User']['new_password_confirm']) {
                $oldPassword = Security::hash($this->request->data['User']['password'], null, true);
                $user = $this->User->getItem($this->Session->read('Auth.User.id'));
                if ($oldPassword == $user['User']['password']) {
                    $this->request->data['User']['password'] = Security::hash($this->request->data['User']['new_password'], null, true);
                    $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
                    if ($this->User->save($this->request->data, false)) {
                        $this->set('success', 1);
                        $this->App->setMessage(__('Password changed'), 'success');
                    }
                } else {
                    $this->App->setMessage(__('Wrong old password. Please try again'), 'error');
                }
            } else {
                $this->App->setMessage(__('Passwords do not match. Please try again'), 'error');
            }
        }
        unset($this->request->data['User']['password']);
    }

    public function disable()
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

        $user["User"]["status"] = $this->request->data["active"] == "yes" ? 1 : 2;

        $this->User->save($user, false);

        echo json_encode(array("success")); exit;
    }

    /**
     * Handles user data:
     *
     *  - Username
     *  - First name
     *  - Last name
     *  - Date of birth
     *  - Address
     *  - Zip/Postal code
     *  - City
     *  - Country
     *  - Mobile number
     *  - Bank name
     *  - Bank shortcode
     *  - Account number
     *  - Referral Code
     *
     * @return void
     */
    public function account()
    {
        if ($this->theme == "ChalkPro") {
            /** Set rest layout */
            $this->layout = 'user-tools';
        } else {
            $this->layout = 'user-panel';
        }

        if (!empty($this->request->data))
        {
            $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');

            $this->User->validator()->remove('date_of_birth');

            if(isset($this->request->data['User']['username'])) {
                unset($this->request->data['User']['username']);
            }

            if(isset($this->request->data['User']['first_name'])) {
                unset($this->request->data['User']['first_name']);
            }

            if(isset($this->request->data['User']['last_name'])) {
                unset($this->request->data['User']['last_name']);
            }

            if(isset($this->request->data['User']['date_of_birth'])) {
                unset($this->request->data['User']['date_of_birth']);
            }

            if ($this->User->save($this->request->data)) {
                $this->App->setMessage(__('Account information updated.', true), 'success');
            }
        }

        $options['fields'] = array(
            0   =>  'User.id',
            1   =>  'User.username',
            3   =>  'User.first_name',
            4   =>  'User.last_name',
            5   =>  'User.date_of_birth',
            6   =>  'User.address1',
            7   =>  'User.address2',
            8   =>  'User.zip_code',
            9   =>  'User.city',
            10  =>  'User.country',
            11  =>  'User.mobile_number',
            12  =>  'User.bank_name',
            13  =>  'User.account_number'
        );


        $user = $this->User->getItem($this->Auth->user('id'));

        $this->set('user', $user['User']);

        $this->set('countries', $this->User->getCountriesList());
    }

    public function docs()
    {
        $this->layout = 'user-panel';

        if(!empty($this->request->data) && isset($this->request->data["User"]["file"]) && is_array($this->request->data["User"]["file"]))
        {
            $files = array();
            $maxsize    = 2097152;
            $acceptable = array(
                'application/pdf',
                'image/jpeg',
                'image/jpg',
                'image/png'
            );

            foreach ($this->request->data["User"]["file"] AS $file) {
                if(($file["size"] >= $maxsize) || ($file["size"] == 0)) {
                    $this->App->setMessage(__('File too large. File must be less than 2 megabytes.', true), 'error');
                    $this->redirect(array('plugin' => null, 'controller' => 'users', 'action' => 'docs'));
                }

                if(!in_array($file['type'], $acceptable)) {
                    $this->App->setMessage(__('Invalid file type. Only PDF, JPG, GIF and PNG types are accepted.', true), 'error');
                    $this->redirect(array('plugin' => null, 'controller' => 'users', 'action' => 'docs'));
                }

                $CacheFolder = new Folder();

                if(!$CacheFolder->cd(TMP . 'uploads')) {
                    new Folder(TMP . 'uploads', true, 0777);
                }

                $extension = explode(".", $file["name"]);
                $path = TMP . 'uploads' . DS . time() . '.' . end($extension);

                $status = move_uploaded_file($file['tmp_name'], $path);

                if ($status) {
                    $files[] = $path;
                }
            }

            if ($this->Email->sendMail('user_docs', Configure::read('Settings.user_doc_upload_email'), array('content' => __('User %s ( ID: %d ) is sending documentation files through user panel on website.', CakeSession::read('Auth.User.username'), CakeSession::read('Auth.User.id'))), $files)) {
                $this->App->setMessage(__('Documents sent successfully.', true), 'success');
            } else {
                $this->App->setMessage(__('Documents were not sent. Please contact try again or contact support.', true), 'error');
            }

            $this->redirect(array('plugin' => null, 'controller' => 'users', 'action' => 'docs'));
        }
    }

    /**
     * User settings:
     *  - Odds Type
     *  - Time zone
     *  - Language
     *
     * @return void
     */
    public function settings()
    {
        $this->layout = 'user-panel';

        $this->User->contain();

        $User = $this->User->find('first', array(
            'fields'    =>  array(
                'User.time_zone',
                'User.language_id',
                'User.odds_type'
            ),
            'conditions'    =>  array(
                'User.id' => $this->Session->read('Auth.User.id')
            )
        ));

        if (!empty($this->request->data))
        {
            $User['User']['id'] = $this->Session->read('Auth.User.id');
            $OddsTypes = $this->Bet->getOddsTypes();

            if (isset($this->request->data['User']['odds_type']) && isset($OddsTypes[$this->request->data['User']['odds_type']]))
            {
                $this->Session->write('Auth.User.odds_type', $this->request->data['User']['odds_type']);
                $User['User']['odds_type'] = $this->request->data['User']['odds_type'];
                $this->Session->write('Auth.User.odds_type', $this->request->data['User']['odds_type']);
            }

            if (isset($this->request->data['User']['time_zone'])) {
                $User['User']['time_zone'] = $this->request->data['User']['time_zone'];
                $this->Session->write('Auth.User.time_zone', $this->request->data['User']['time_zone']);
            }

            if (isset($this->request->data['User']['language_id'])) {
                $User['User']['language_id'] = $this->request->data['User']['language_id'];
                $this->Session->write('Auth.User.language_id', $this->request->data['User']['language_id']);
                Configure::write('Config.language', $this->Language->getSlugById(CakeSession::read('Auth.User.language_id')));
            }

            if($this->User->save($User, false)) {
                $this->App->setMessage(__('Account settings updated.', true), 'success');
            }else{
                $this->App->setMessage(__('Cannot save settings. Please try again after few moments.', true), 'error');
            }

            $this->redirect(array('language' =>  $this->Language->getSlugById(CakeSession::read('Auth.User.language_id')), 'plugin' => false, 'controller' => 'users', 'action' => 'settings'));
            exit;
        }

        $this->data = $User;
        $this->set('locales', $this->User->Language->getLanguagesListIds());
        $this->set('odd_types', $this->Bet->getOddsTypes());
        $this->set('User', $User);
    }

    public function admin_settings()
    {
        $this->settings();
        $this->set('tabs', array());
    }

    /**
     * User Password Reset
     *
     * @return void
     */
    public function reset()
    {
        $this->layout = 'user-tools';

        $step = !empty($this->params['named']['step']) ? $this->params['named']['step'] : 1;

        switch ($step) {
            case 2:
                $code = $this->params['named']['code'];

                $options['conditions'] = array(
                    'User.confirmation_code' => $code
                );
                $this->User->contain();

                $user = $this->User->find('first', $options);

                if (empty($user['User'])) {
                    $this->redirect(array('plugin' => null, 'action' => 'reset'), null, true);
                }

                if (!empty($this->request->data)) {
                    $password = $this->request->data['User']['password'];
                    $this->request->data['User']['password'] = '';

                    if ($password == $this->request->data['User']['password_confirm']) {
                        $code = $this->request->data['User']['code'];
                        $options['conditions'] = array(
                            'User.confirmation_code' => $code
                        );

                        $this->User->contain();

                        $user = $this->User->find('first', $options);

                        $user['User']['confirmation_code'] = '';
                        $user['User']['password'] = Security::hash($this->request->data['User']['password_confirm'], null, true);
                        $this->User->save($user, false);

                        $this->App->setMessage(__('Password changed', true), 'success');
                        $this->set('success', 1);
                    }
                    else {
                        $this->App->setMessage(__('Passwords do not match. Please try again', true), 'error');
                    }
                }

                $this->set('code', $code);

                $this->render('reset-password');
                break;
            default:
                if (!empty($this->request->data['User']['email'])) {
                    if (!VerifyRecaptcha::verify($this->request->data["g-recaptcha-response"])->success) {
                        $this->User->invalidate('recaptcha');
                    } else {
                        $this->User->contain();
                        $user = $this->User->findByEmail($this->request->data['User']['email']);
                        if ($user != null) {
                            $user['User']['confirmation_code'] = $this->Utils->getRandomString(16);
                            $this->User->save($user, false);
                            $url = Router::url(array('language' => Configure::read('Config.language'), 'controller' => 'users', 'action' => 'reset', 'code' => $user['User']['confirmation_code'], 'step' => 2), true);
                            $link = '<a href="' . $url . '">' . $url . '</a>';

                            $user['User']['link'] = $link;
                            $this->Email->sendMail('passwordReset', $user['User']['email'], $user['User']);
                            $this->App->setMessage(__('Password reset link sent to your email', true), 'info');
                            $this->set('success', 1);
                        } else {
                            $this->App->setMessage(__('E-mail not valid', true), 'error');
                        }
                    }
                }else{
                    $this->App->setMessage(__('E-mail not valid', true), 'error');
                }
                break;
        }
    }

    /**
     * User promo code
     *
     * @return void
     */
    public function bonus()
    {
        /** Set rest layout */
        $this->layout = 'user-panel';

        if (!empty($this->request->data))
        {
            $code = $this->request->data['User']['bonus_code'];
            $bonusCode = $this->BonusCode->findBonusCode($code);

            if (!empty($bonusCode))
            {
                $userId = $this->Session->read('Auth.User.id');
                $bonusCodeId = $bonusCode['BonusCode']['id'];
                $used = $this->BonusCodesUser->findBonusCode($bonusCodeId, $userId);

                if (empty($used)) {
                    $this->User->addFunds($userId, $bonusCode['BonusCode']['amount']);
                    $this->BonusCode->useCode($bonusCodeId);
                    $this->BonusCodesUser->addCode($bonusCodeId, $userId);
                    $this->set('success', true);
                    $this->App->setMessage(__('Promotional code successfully used', true), 'success');
                } else {
                    $this->App->setMessage(__('You have already used this promotion code.', true), 'error');
                }
            }
            else {
                $this->App->setMessage(__('Invalid promotional code', true), 'error');
            }
        }
    }

    /**
     *  User login
     */
    public function login() {

        $this->layout = "user-tools";

        if ($this->request->is('post')) {

            $this->Auth->login();

            if ($this->Auth->user('status') == 0) {
                $this->Auth->logout();
                $this->App->setMessage(__('Invalid login details or not confirmed account.'), 'info');
                $this->redirect(array('plugin' => null, 'controller' => 'users', 'action' => 'login'));
            }

            if ($this->Auth->user('status') == 2) {
                $this->Auth->logout();
                $this->App->setMessage(__('You account is suspended. Please contact support team.'), 'info');
                $this->redirect(array('plugin' => null, 'controller' => 'users', 'action' => 'login'));
            }

            $this->getEventManager()->dispatch(new CakeEvent('Controller.Users.login', $this, array(
                'userId'    =>  $this->Auth->user('id'),
                'groupId'   =>  $this->Auth->user('group_id')
            )));

            $this->User->updateLastVisit($this->Auth->user('id'));

            $this->redirect('/' . Configure::read('Config.language'));
        }

        $this->set('request_pass_params', $this->request->params["pass"]);
    }

    /**
     * Admin login
     */
    public function admin_login()
    {
        if($this->Auth->loggedIn()) {
            $this->redirect(array('plugin' => null, 'controller' => 'dashboard'), 302, true);
        }

        $this->Session->write('Auth.Acos', null);

        $this->layout = 'admin_login';

        $this->set('groups', $this->Group->getAdminGroups());
        $this->set('languages', $this->Language->getLanguagesList2());
        $this->set('model', $this->User->name);

        if (!empty($this->request->data)) {
            if(!$this->Auth->login()) {
                $this->Admin->setMessage(__('Username or password is incorrect'), 'error');
                $this->redirect(array('plugin' => null, 'controller' => 'users', 'action' => 'logout'), 302, true);
            }

            if ($this->Session->read('Auth.User.group_id') != $this->request->data['User']['group_id']) {
                $this->Admin->setMessage(__('Username or password is incorrect'), 'error');
                $this->redirect(array('plugin' => null, 'controller' => 'users', 'action' => 'logout'), 302, true);
            }

            $language = isset($this->request->data["User"]["language_id"]) ? $this->request->data["User"]["language_id"] : Configure::read('Config.language');

            Configure::write('Config.language', $language);

            $this->getEventManager()->dispatch(new CakeEvent('Controller.Users.login', $this, array(
                'userId'    =>  $this->Auth->user('id'),
                'groupId'   =>  $this->Auth->user('group_id')
            )));

            $this->User->updateLastVisit($this->Auth->user('id'));

            $this->redirect(array('language' => $language, 'plugin' => null, 'controller' => 'dashboard'));
        }
    }

    /**
     * User logout action
     */
    public function logout() {
        $this->Auth->logout();
        $this->redirect($this->Auth->logoutRedirect, 302, true);
    }

    public function admin_logout() {
        if($this->Auth->loggedIn()) { $this->Auth->logout(); }
        $this->redirect(array('plugin' => null, 'admin' => true, 'action' => 'login'), 302, true);
    }

    /**
     * Updates user odds type
     *
     */
    public function changeOddsType()
    {
        $oddType = isset($this->request->params["pass"][0]) ? $this->request->params["pass"][0] : null;

        if(!is_string($oddType) || !in_array(ucfirst($oddType), $this->Bet->getOddsTypes()) || !$this->Auth->user()) {
            $this->redirect($this->referer());
            exit;
        }

        if($this->Session->read('Auth.User.odds_type') ==  array_search(ucfirst($oddType), $this->Bet->getOddsTypes())) {
            $this->redirect($this->referer());
            exit;
        }

        $oddType = array_search(ucfirst($oddType), $this->Bet->getOddsTypes());

        $this->Session->write('Auth.User.odds_type', $oddType);

        $this->User->updateAll(
            array('User.odds_type'  => $oddType),
            array('User.id '        => $this->Session->read('Auth.User.id'))
        );

        $this->redirect($this->referer());
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
        if (!empty($this->request->data['User'])) {
            $this->request->data['User']['password']            = Security::hash($this->request->data['User']['password_raw'], null, true);
            $this->request->data['User']['status']              = 1;
            $this->request->data['User']['last_visit']          = gmdate('Y-m-d H:i:s');
            $this->request->data['User']['registration_date']   = gmdate('Y-m-d H:i:s');
            $this->request->data['User']['group_id']            = Group::USER_GROUP;
            $this->request->data['User']['odds_type']           = 1;
        }

        parent::admin_add();

        $this->request->data['User']['password'] = '';
    }

    /**
     * Admin Edit scaffold functions
     *
     * @param int $id - edit item id
     *
     * @return void
     */
    public function admin_edit($id)
    {
        if (!empty($this->request->data)) {
            $user = $this->User->getItem($id);
            $this->request->data['User']['password'] = $user['User']['password'];
        }
        parent::admin_edit($id);
    }


    public function admin_tickets($id)
    {
        parent::admin_index(array('Ticket.user_id' => $id), 'Ticket');
        $this->viewPath = 'users';
    }

    public function admin_deposits($id)
    {
        parent::admin_index(array('Deposit.user_id' => $id), 'Deposit');
        $this->viewPath = 'users';
    }

    public function admin_withdraws($id)
    {
        parent::admin_index(array('Withdraw.user_id' => $id), 'Withdraw');
        $this->viewPath = 'users';
    }

    public function admin_deposit_bonus_history($id)
    {
        //FIXME: someday in traint (php 5.4)
        $this->view = "admin_index";
        $conditions['user_id'] = $id;
        $ret = parent::admin_index($conditions, 'PaymentBonusUsage');
        return $ret;
    }

    /**
     * Operator panel
     */
    public function operator_panel()
    {
        $todayDeposits = $this->User->Deposit->find('count', array(
            'conditions'    =>  array(
                'Deposit.user_id'               =>  $this->Session->read('Auth.User.id'),
                'Deposit.date BETWEEN ? AND ?'  =>  array(gmdate('Y-m-d'), gmdate('Y-m-d H:i:s'))
            )
        ));

        $todayWithdraws = $this->User->Withdraw->find('count', array(
            'conditions'    =>  array(
                'Withdraw.user_id'              =>  $this->Session->read('Auth.User.id'),
                'Withdraw.date BETWEEN ? AND ?' =>  array(gmdate('Y-m-d'), gmdate('Y-m-d H:i:s'))
            )
        ));

        $checkedTickets = 0;

        $todayTickets = $this->User->Ticket->find('count', array(
            'conditions'    =>  array(
                'Ticket.user_id'              =>  $this->Session->read('Auth.User.id'),
                'Ticket.date BETWEEN ? AND ?' =>  array(gmdate('Y-m-d'), gmdate('Y-m-d H:i:s'))
            )
        ));

        $paidTickets = $this->User->Ticket->find('count', array(
            'conditions'    =>  array(
                'Ticket.user_id'    =>  $this->Session->read('Auth.User.id'),
                'Ticket.paid'       =>  Ticket::PAID
            )
        ));

        $this->User->Ticket->contain();

        $lastTickets = $this->User->Ticket->find('all', array(
            'conditions'    =>  array(
                'Ticket.user_id'              =>  $this->Session->read('Auth.User.id'),
            ),
            'order' =>  array('Ticket.date'),
            'limit' =>  7
        ));

        $this->set('todayDeposits', $todayDeposits);
        $this->set('todayWithdraws', $todayWithdraws);
        $this->set('checkedTickets', $checkedTickets);
        $this->set('todayTickets', $todayTickets);
        $this->set('paidTickets', $paidTickets);
        $this->set('lastTickets', $lastTickets);

        $this->layout = 'empty';
    }
}