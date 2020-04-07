<?php
/**
 * Front Reports.Reports Controller
 *
 * Handles Reports.Reports Actions
 *
 * @package    Reports.Controller
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('ReportsAppController', 'Reports.Controller');

class ReportsController extends ReportsAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Reports';

    /**
     * Additional models
     *
     * @var array
     */
    public $uses = array(
        0   =>  'Reports.Report',
        1   =>  'Deposit',
        2   =>  'Ticket',
        4   =>  'User',
        5   =>  'Group'
    );

    /**
     * Default begin report start date
     *
     * @var string $beginOfDay
     */
    public $beginOfDay = null;

    /**
     * Default begin report end date
     *
     * @var string $endOfDay
     */
    public $endOfDay = null;

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $beginOfDay = isset($this->request->data['Report']['from'])   ? strtotime($this->request->data['Report']['from']) : strtotime("midnight", time());
        $endOfDay   = isset($this->request->data['Report']['to'])     ? strtotime($this->request->data['Report']['to']) : strtotime("tomorrow", $beginOfDay) - 1;

        $this->beginOfDay = gmdate('Y-m-d H:i:s', $beginOfDay);
        $this->endOfDay   = gmdate('Y-m-d H:i:s', $endOfDay);

        $this->Auth->allow(array('admin_tickets', 'admin_staff', 'admin_user', 'admin_agent', 'admin_agents'));

        parent::beforeFilter();
    }

    private function admin_branch_agents()
    {
        $Agents = $this->User->find('all', array(
            'contain'       =>  array(
                'Ticket'=>  array('fields' => array('status', 'amount', 'return')),
                'Group' =>  array('fields' => array('name'))
            ),
            'fields'        =>  array(
                'username'
            ),
            'conditions'    =>  array(
                'User.group_id'     =>  Group::AGENT_GROUP,
                'User.referal_id'   =>  CakeSession::read("Auth.User.id")
            )
        ));

        foreach ($Agents AS $agentI => $Agent) {

            $Users = $this->User->find('all', array(
                'contain'       =>  array(
                    'Ticket'=>  array('fields' => array('status', 'amount', 'return')),
                    'Group' =>  array('fields' => array('name'))
                ),
                'fields'        =>  array(
                    'username'
                ),
                'conditions'    =>  array(
                    'User.group_id'     =>  Group::USER_GROUP,
                    'User.referal_id'   =>  $Agent["User"]["id"]
                )
            ));

            foreach ($Users AS $userI => $User) {

                $Users[$userI]["User"]["Income"] = array_sum(array_map(
                    function($Ticket) {
                        if($Ticket['status'] == Ticket::TICKET_STATUS_LOST) { return $Ticket['amount']; }
                        if($Ticket['status'] == Ticket::TICKET_STATUS_CANCELLED) { return $Ticket['amount']; }
                        return 0;
                    }
                    , $User["Ticket"]));

                $Users[$userI]["User"]["PendingIncome"] = array_sum(array_map(
                    function($Ticket) {
                        if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) { return $Ticket['amount']; }
                        return 0;
                    }
                    , $User["Ticket"]));

                $Users[$userI]["User"]["Outcome"] = array_sum(array_map(
                    function($Ticket) {
                        if($Ticket['status'] == Ticket::TICKET_STATUS_WON) { return $Ticket['return']; }
                        if($Ticket['status'] == Ticket::TICKET_STATUS_CANCELLED) { return $Ticket['amount']; }
                        return 0;
                    }
                    , $User["Ticket"]));

                $Users[$userI]["User"]["PendingOutcome"] = array_sum(array_map(
                    function($Ticket) {
                        if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) { return $Ticket['return']; }
                        return 0;
                    }
                    , $User["Ticket"]));

                if (!isset($Agents[$agentI]["User"]["Income"])) {
                    $Agents[$agentI]["User"]["Income"] = 0;
                }

                if (!isset($Agents[$agentI]["User"]["Outcome"])) {
                    $Agents[$agentI]["User"]["Outcome"] = 0;
                }

                if (!isset($Agents[$agentI]["User"]["PendingIncome"])) {
                    $Agents[$agentI]["User"]["PendingIncome"] = 0;
                }

                if (!isset($Agents[$agentI]["User"]["PendingOutcome"])) {
                    $Agents[$agentI]["User"]["PendingOutcome"] = 0;
                }

                $Agents[$agentI]["User"]["Income"]  += $Users[$userI]["User"]["Income"];
                $Agents[$agentI]["User"]["Outcome"] += $Users[$userI]["User"]["Outcome"];
                $Agents[$agentI]["User"]["PendingIncome"]   += $Users[$userI]["User"]["PendingIncome"];
                $Agents[$agentI]["User"]["PendingOutcome"]  +=  $Users[$userI]["User"]["PendingOutcome"];

                unset($Users[$userI]["Group"]);
                unset($Users[$userI]["Ticket"]);
            }
            unset($Agents[$agentI]["Ticket"]);
            $Agents[$agentI]["User"]["Users"] = $Users;
        }

        $this->set('show', true);

        $this->layout = 'empty';

        $this->set('user', $this->Auth->user());
        $this->set('interval', array($this->beginOfDay, $this->endOfDay));
        $this->set('agents', $Agents);

    }

    /**
     * Index of tickets report generate
     *
     * @return array|void
     */
    public function admin_tickets($type =null)
    {
        if ($type == 'branch') {
            return $this->admin_branch_agents();
        }

        $conditions =  array(
            'contain'   =>  array('User'),
            'conditions'    =>  array(
                'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay)
            )
        );

        if($this->Auth->user('Group.id') != Group::ADMINISTRATOR_GROUP) {
            $referrals = array_map(function($user){
                return $user["User"]["id"];
            }, (array) $this->User->find('all', array(
                "contain"       =>  array(),
                'fields'        =>  array(
                    'id'
                ),
                'conditions'    =>  array(
                    'User.referal_id' =>  $this->Auth->user('id')
                )
            )));

            $conditions["conditions"]['Ticket.user_id'] = (array) $referrals;
        }

        $Tickets = $this->Ticket->find('all', $conditions);

        $Income = array_map(
            function($Ticket) {
                if($Ticket['Ticket']['status'] == Ticket::TICKET_STATUS_LOST) { return $Ticket['Ticket']['amount']; }
                if($Ticket['Ticket']['status'] == Ticket::TICKET_STATUS_CANCELLED) { return $Ticket['Ticket']['amount']; }
                return 0;
            }
            , $Tickets);

        $PendingIncome = array_map(
            function($Ticket) {
                if($Ticket['Ticket']['status'] == Ticket::TICKET_STATUS_PENDING) { return $Ticket['Ticket']['amount']; }
                return 0;
            }
            , $Tickets);

        $Outcome = array_map(
            function($Ticket) {
                if($Ticket['Ticket']['status'] == Ticket::TICKET_STATUS_WON) { return $Ticket['Ticket']['return']; }
                if($Ticket['Ticket']['status'] == Ticket::TICKET_STATUS_CANCELLED) { return $Ticket['Ticket']['amount']; }
                return 0;
            }
            , $Tickets);

        $PendingOutcome = array_map(
            function($Ticket) {
                if($Ticket['Ticket']['status'] == Ticket::TICKET_STATUS_PENDING) { return $Ticket['Ticket']['return']; }
                return 0;
            }
            , $Tickets);

        if(isset($this->request->data['Report']['from']) || isset($this->request->data['Report']['to'])) {
            $this->layout = 'empty';
            $this->set('show', true);
        }else{
            $this->set('show', false);
        }

        $this->set('user', $this->Auth->user());
        $this->set('interval', array($this->beginOfDay, $this->endOfDay));

        $this->set('Tickets', $Tickets);

        $this->set('Income', array_sum($Income));
        $this->set('Outcome', array_sum($Outcome));
        $this->set('PendingIncome', array_sum($PendingIncome));
        $this->set('PendingOutcome', array_sum($PendingOutcome));
    }

    /**
     * Index of staff report generate
     *
     * @return array|void
     */
    public function admin_staff() {

        if(isset($this->request->data['Report']['from']) || isset($this->request->data['Report']['to'])) {
            $this->layout = 'empty';
            $this->set('show', true);
        }else{
            $this->set('show', false);
        }

        $Staffs = $this->User->find('all', array(
            'contain'       =>  array(
                'Ticket'=>  array('fields' => array('status', 'amount', 'return')),
                'Group' =>  array('fields' => array('name'))
            ),
            'fields'        =>  array(
                'username'
            ),
            'conditions'    =>  array(
                'User.group_id' =>  Group::AGENT_GROUP
            )
        ));

        $Staffs = array_map(function($Staff){
            $Staff['Stats']['income'] = array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_LOST || $Ticket['status'] == Ticket::TICKET_STATUS_CANCELLED) {
                    return $Ticket['amount'];
                }
                return 0;
            }, $Staff['Ticket']));

            $Staff['Stats']['outcome'] = array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_WON) {
                    return $Ticket['return'];
                }elseif( $Ticket['status'] == Ticket::TICKET_STATUS_CANCELLED) {
                    return $Ticket['amount'];
                }
                return 0;
            }, $Staff['Ticket']));

            $Staff['Stats']['balance'] =  $Staff['Stats']['income'] -  $Staff['Stats']['outcome'];

            $Staff['Stats']['pending'] = array();
            $Staff['Stats']['pending'][0] = array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) {
                    return $Ticket['amount'];
                }
                return 0;
            }, $Staff['Ticket']));
            $Staff['Stats']['pending'][1] = array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) {
                    return $Ticket['return'];
                }
                return 0;
            }, $Staff['Ticket']));

            return $Staff;

        }, $Staffs);


        $StaffIncome = array_sum(array_map(function($Staff){
            return $Staff['Stats']['income'];
        }, $Staffs));

        $StaffOutcome = array_sum(array_map(function($Staff){
            return $Staff['Stats']['outcome'];
        }, $Staffs));

        $PendingIncome = array_sum(array_map(function($Staff){
            return array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) {
                    return $Ticket['amount'];
                }
                return 0;
            }, $Staff['Ticket']));
        }, $Staffs));

        $PendingOutcome = array_sum(array_map(function($Staff){
            return array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) {
                    return $Ticket['return'];
                }
                return 0;
            }, $Staff['Ticket']));
        }, $Staffs));


        $this->set('user', $this->Auth->user());
        $this->set('interval', array($this->beginOfDay, $this->endOfDay));

        $this->set('Staffs', $Staffs);

        $this->set('StaffIncome', $StaffIncome);
        $this->set('StaffOutcome', $StaffOutcome);

        $this->set('PendingIncome', $PendingIncome);
        $this->set('PendingOutcome', $PendingOutcome);
    }

    /**
     * Index of user report generate
     *
     * @return array|void
     */
    public function admin_user() {

        if(isset($this->request->data['Report']['from']) || isset($this->request->data['Report']['to'])) {
            $this->layout = 'empty';
            $this->set('show', true);
        }else{
            $this->set('show', false);
        }

        $conditions = array(
            'contain'       =>  array(
                'Ticket'=>  array('fields' => array('status', 'amount', 'return')),
                'Group' =>  array('fields' => array('name'))
            ),
            'fields'        =>  array(
                'username'
            ),
            'conditions'    =>  array(
                'User.group_id' =>  Group::USER_GROUP
            )
        );

        if($this->Auth->user('Group.id') != Group::ADMINISTRATOR_GROUP) {
            $conditions["conditions"]['User.referal_id'] = $this->Auth->user('id');
        }

        $Staffs = $this->User->find('all', $conditions);

        $Staffs = array_map(function($Staff){
            $Staff['Stats']['income'] = array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_LOST || $Ticket['status'] == Ticket::TICKET_STATUS_CANCELLED) {
                    return $Ticket['amount'];
                }
                return 0;
            }, $Staff['Ticket']));

            $Staff['Stats']['outcome'] = array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_WON) {
                    return $Ticket['return'];
                }elseif( $Ticket['status'] == Ticket::TICKET_STATUS_CANCELLED) {
                    return $Ticket['amount'];
                }
                return 0;
            }, $Staff['Ticket']));

            $Staff['Stats']['balance'] =  $Staff['Stats']['income'] -  $Staff['Stats']['outcome'];

            $Staff['Stats']['pending'] = array();
            $Staff['Stats']['pending'][0] = array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) {
                    return $Ticket['amount'];
                }
                return 0;
            }, $Staff['Ticket']));
            $Staff['Stats']['pending'][1] = array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) {
                    return $Ticket['return'];
                }
                return 0;
            }, $Staff['Ticket']));

            return $Staff;

        }, $Staffs);


        $StaffIncome = array_sum(array_map(function($Staff){
            return $Staff['Stats']['income'];
        }, $Staffs));

        $StaffOutcome = array_sum(array_map(function($Staff){
            return $Staff['Stats']['outcome'];
        }, $Staffs));

        $PendingIncome = array_sum(array_map(function($Staff){
            return array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) {
                    return $Ticket['amount'];
                }
                return 0;
            }, $Staff['Ticket']));
        }, $Staffs));

        $PendingOutcome = array_sum(array_map(function($Staff){
            return array_sum(array_map(function($Ticket) {
                if($Ticket['status'] == Ticket::TICKET_STATUS_PENDING) {
                    return $Ticket['return'];
                }
                return 0;
            }, $Staff['Ticket']));
        }, $Staffs));


        $this->set('user', $this->Auth->user());
        $this->set('interval', array($this->beginOfDay, $this->endOfDay));

        $this->set('Staffs', $Staffs);

        $this->set('StaffIncome', $StaffIncome);
        $this->set('StaffOutcome', $StaffOutcome);

        $this->set('PendingIncome', $PendingIncome);
        $this->set('PendingOutcome', $PendingOutcome);
    }


    private function agents($userId, $from, $to)
    {
        $totalIncome = 0;
        $totalOutcome = 0;
        $totalCommissions = 0;

        $from = date("Y-m-d H:i:s", strtotime("midnight", strtotime($from)));
        $to   = date("Y-m-d H:i:s", strtotime("tomorrow", strtotime($to)) - 1);

        $users = $this->User->find('all', array(
            'contain'       =>  array(),
            'fields'        =>  array('id', 'username', 'balance'),
            'conditions'    =>  array(
                'User.referal_id'   =>  $userId
            )
        ));

        $user = array_map(function($user){
            return $user["User"]["id"];
        }, $users);

        $depositFundStats = $this->Deposit->find('all', array(
                'contain'   =>  array(),
                'group'     => array('Deposit.user_id'),
                'fields'    =>  array(
                    'Deposit.user_id AS user_id',
                    'sum(Deposit.amount) AS total',
                    'count(Deposit.id) AS count'
                ),
                'conditions'    =>  array(
                    'Deposit.date BETWEEN ? AND ?'  => array($from, $to),
                    'Deposit.status'    =>  Deposit::DEPOSIT_STATUS_COMPLETED,
                    'Deposit.type'      =>  Deposit::DEPOSIT_TYPE_OFFLINE,
                    'Deposit.user_id'   =>  $user
                )
            )
        );

        $withdrawChargeStats = $this->Withdraw->find('all', array(
                'contain'   =>  array(),
                'group' => array('Withdraw.user_id'),
                'fields'    =>  array(
                    'Withdraw.user_id AS user_id',
                    'sum(Withdraw.amount) AS total',
                    'count(Withdraw.id) AS count'
                ),
                'conditions'    =>  array(
                    'Withdraw.date BETWEEN ? AND ?'  => array($from, $to),
                    'Withdraw.status'    =>  Withdraw::WITHDRAW_STATUS_COMPLETED,
                    'Withdraw.type'      =>  Withdraw::WITHDRAW_TYPE_OFFLINE,
                    'Withdraw.user_id'   =>  $user
                )
            )
        );

        foreach ($users AS $index => $user ) {
            $users[$index]["User"]['Deposit']["total"] = 0;
            $users[$index]["User"]['Deposit']["count"] = 0;
            $users[$index]["User"]['Withdraw']["total"] = 0;
            $users[$index]["User"]['Withdraw']["count"] = 0;
        }

        foreach ($users AS $index => $user ) {
            foreach ($depositFundStats AS $depositFundStat) {
                if ($user["User"]["id"] == $depositFundStat["Deposit"]["user_id"]) {
                    $users[$index]["User"]['Deposit']["total"] = $depositFundStat[0]["total"];
                    $users[$index]["User"]['Deposit']["count"] = $depositFundStat[0]["count"];
                    $totalIncome += $depositFundStat[0]["total"];
                }
            }
            foreach ($withdrawChargeStats AS $withdrawChargeStat) {
                if ($user["User"]["id"] == $withdrawChargeStat["Withdraw"]["user_id"]) {
                    $users[$index]["User"]['Withdraw']["total"] = $withdrawChargeStat[0]["total"];
                    $users[$index]["User"]['Withdraw']["count"] = $withdrawChargeStat[0]["count"];
                    $totalOutcome += $withdrawChargeStat[0]["total"];
                }
            }
        }


        foreach ($users AS $index => $user ) {
            $users[$index]["User"]['Commissions'] = $user['User']['balance'] > 0  ? 0 : (float) abs($this->Utils->percentage($user['User']['Deposit']['total'] - $user['User']['Withdraw']['total'], Configure::read('Settings.commission')));
            $totalCommissions += $users[$index]["User"]['Commissions'];

        }

        return array(
            'users'             =>  $users,
            'totalCommissions'  =>  $totalCommissions,
            'totalIncome'       =>  $totalIncome,
            'totalOutcome'      =>  $totalOutcome
        );
    }

    public function admin_agents()
    {
        if(isset($this->request->data['Report']['from']) || isset($this->request->data['Report']['to']))
        {
            $agents = $this->User->find('all', array(
                'contain'       =>  array(),
                'fields'        =>  array('id', 'username'),
                'conditions'    =>  array(
                    'User.group_id'   =>  Group::AGENT_GROUP
                )
            ));

            $totals["totalIncome"] = 0;
            $totals["totalOutcome"] = 0;
            $totals["totalCommissions"] = 0;

            foreach ($agents AS $i => $agent)
            {
                $users = $this->agents($agent["User"]["id"], $this->request->data['Report']['from'], $this->request->data['Report']['to']);

                $agents[$i]["Stats"] = array(
                    "totalIncome"       =>  $users["totalIncome"],
                    "totalOutcome"      =>  $users["totalOutcome"],
                    "totalCommissions"  =>  $users["totalCommissions"],
                );

                $totals["totalIncome"] += $users["totalIncome"];
                $totals["totalOutcome"] += $users["totalOutcome"];
                $totals["totalCommissions"] += $users["totalCommissions"];
            }

            $this->set('user', $this->Auth->user());
            $this->set('interval', array($this->request->data['Report']['from'], $this->request->data['Report']['to']));
            $this->set('users2', $agents);
            $this->set('totalIncome', $totals["totalIncome"]);
            $this->set('totalOutcome', $totals["totalOutcome"]);
            $this->set('totalCommissions', $totals["totalCommissions"]);

            $this->layout = 'empty';
            $this->set('show', true);
        }else{
            $this->set('show', false);
        }
    }

    public function admin_agent()
    {
        if(isset($this->request->data['Report']['from']) || isset($this->request->data['Report']['to']))
        {
            $result = $this->agents($this->Auth->user('id'), $this->request->data['Report']['from'], $this->request->data['Report']['to']);

            $this->set('user', $this->Auth->user());
            $this->set('interval', array($this->request->data['Report']['from'], $this->request->data['Report']['to']));
            $this->set('users2', $result["users"]);
            $this->set('totalIncome', $result["totalIncome"]);
            $this->set('totalOutcome', $result["totalOutcome"]);

            $this->layout = 'empty';
            $this->set('show', true);
        }else{
            $this->set('show', false);
        }
    }
    /**
     * Daily cashier report
     */
    public function admin_cashier_daily() {

        $user  = $this->Auth->user('id');
        $users = $this->Reports->usersSelection(Group::CASHIER_GROUP);

        if($this->request->is('post') && isset($this->request->data['Report']['user_id'])) {
            if(in_array($this->request->data['Report']['user_id'], $users)) {
                $user = $this->request->data['Report']['user_id'];
            }else{
                if($this->request->data['Report']['user_id'] == 0) {
                    if($this->Auth->user('Group.id') == Group::ADMINISTRATOR_GROUP) {
                        $user = array_filter(array_merge(array($user), array_keys($users)));
                    }
                }
            }
        }

        $depositFundStats = $this->Deposit->find('first', array(
                'contain'   =>  array(),
                'fields'    =>  array(
                    'sum(Deposit.amount) AS total',
                    'count(Deposit.id) AS count'
                ),
                'conditions'    =>  array(
                    'Deposit.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Deposit.status'    =>  Deposit::DEPOSIT_STATUS_COMPLETED,
                    'Deposit.type'      =>  Deposit::DEPOSIT_TYPE_OFFLINE,
                    'Deposit.user_id'   =>  $user
                )
            )
        );

        $withdrawChargeStats = $this->Withdraw->find('first', array(
                'contain'   =>  array(),
                'fields'    =>  array(
                    'sum(Withdraw.amount) AS total',
                    'count(Withdraw.id) AS count'
                ),
                'conditions'    =>  array(
                    'Withdraw.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Withdraw.status'    =>  Withdraw::WITHDRAW_STATUS_COMPLETED,
                    'Withdraw.type'      =>  Withdraw::WITHDRAW_TYPE_OFFLINE,
                    'Withdraw.user_id'   =>  $user
                )
            )
        );

        if(isset($this->request->data['Report']['from']) || isset($this->request->data['Report']['to'])) {
            $this->layout = 'empty';
            $this->set('show', true);
        }else{
            $this->set('show', false);
        }

        $this->set('user', $this->Auth->user());
        $this->set('interval', array($this->beginOfDay, $this->endOfDay));

        $this->set('depositFundStats', $depositFundStats);
        $this->set('withdrawChargeStats', $withdrawChargeStats);

        $this->set('users', $users);

        $this->set('chartsData', array(
            0   =>  array(
                'settings'  =>  array(
                    'id'            =>  0,
                    'title'         =>  null,
                    'showTitle'     =>  false
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label' =>  __('Funded'),
                        'count' =>  $depositFundStats[0]['count']
                    ),
                    1   =>  array(
                        'label' =>  __('Charged'),
                        'count' =>  $withdrawChargeStats[0]['count'],
                        'label_radius'  =>  100
                    )
                )
            )
        ));
    }

    /**
     * Operator daily report
     */
    public function admin_operator_daily() {

        $user  = $this->Auth->user('id');
        $users = $this->Reports->usersSelection(Group::OPERATOR_GROUP);

        if(isset($this->request->data['Report']['user_id'])) {
            if(isset($users[$this->request->data['Report']['user_id']])) {

                if($this->request->data['Report']['user_id'] == 0) {
                    if($this->Auth->user('Group.id') == Group::ADMINISTRATOR_GROUP) {
                        $user = array_filter(array_merge(array($user), array_keys($users)));
                    }
                }else{
                    $user = $this->request->data['Report']['user_id'];
                }
            }
        }

        $ticketsTotalCount = $this->Ticket->find('count', array(
                'contain'   =>  array(),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.user_id'   =>  $user
                )
            )
        );

        $singleTicketStats = $this->Ticket->find('first', array(
                'contain'   =>  array(),
                'fields'    =>  array(
                    'count(Ticket.id) AS count',
                    'sum(Ticket.amount) AS total'
                ),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.user_id'    =>  $user,
                    'Ticket.type'       =>  Ticket::TICKET_TYPE_SINGLE
                )
            )
        );

        $multiTicketStats = $this->Ticket->find('first', array(
                'contain'   =>  array(),
                'fields'    =>  array(
                    'count(Ticket.id) AS count',
                    'sum(Ticket.amount) AS total'
                ),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.user_id'    =>  $user,
                    'Ticket.type'       =>  Ticket::TICKET_TYPE_MULTI
                )
            )
        );

        $withdrawsOutcome = $this->Ticket->find('first', array(
                'contain'   =>  array(),
                'fields'    =>  array(
                    'count(Ticket.id) AS count',
                    'sum(Ticket.return) AS total'
                ),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.user_id'    =>  $user,
                    'Ticket.paid'       =>  Ticket::TICKET_CONDITION_PAID
                )
            )
        );

        $this->set('chartsData', array(
            0   =>  array(
                'settings'  =>  array(
                    'id'            =>  0,
                    'title'         =>  __('Ticket types'),
                    'label_radius'  =>  75,
                    'radius'        =>  75
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label' =>  __('Single stake'),
                        'count' =>  $singleTicketStats[0]['total'],
                    ),
                    1   =>  array(
                        'label' =>  __('Multi stake'),
                        'count' =>  $multiTicketStats[0]['total'],
                    )
                )
            ),

            1   =>  array(
                'settings'  =>  array(
                    'id'            =>  1,
                    'label'         =>  __('User finance activity'),
                    'label_radius'  =>  75,
                    'radius'        =>  75
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label' =>  __('Single'),
                        'count' =>  $singleTicketStats[0]['count'],
                    ),
                    1   =>  array(
                        'label' =>  __('Multi'),
                        'count' =>  $multiTicketStats[0]['count'],
                    )
                )
            )
        ));

        $this->set('chartsData1', array(
            0   =>  array(
                'settings'  =>  array(
                    'id'            =>  2,
                    'title'         =>  __('Income / Outcome'),
                    'label_radius'  =>  75,
                    'radius'        =>  75
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label' =>  __('Income'),
                        'count' =>  $singleTicketStats[0]['total'] + $multiTicketStats[0]['total'],
                    ),
                    1   =>  array(
                        'label' =>  __('Outcome'),
                        'count' =>  $withdrawsOutcome[0]['total'],
                    ),
                )
            ),

            1   =>  array(
                'settings'  =>  array(
                    'id'            =>  3,
                    'label'         =>  __('Placed / Withdrawed'),
                    'label_radius'  =>  75,
                    'radius'        =>  75
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label'     =>  __('Placed'),
                        'count'     =>  $singleTicketStats[0]['count'] + $multiTicketStats[0]['count']
                    ),
                    1   =>  array(
                        'label'     =>  __('Withdrawed'),
                        'count'     =>  $withdrawsOutcome[0]['count']
                    )
                )
            )
        ));

        if(isset($this->request->data['Report']['from']) || isset($this->request->data['Report']['to'])) {
            $this->layout = 'empty';
            $this->set('show', true);
        }else{
            $this->set('show', false);
        }

        if(is_array($user) && !empty($user)) {
            $report_user = array('User' => array('username' => 'All operators'));
        }else{
            $report_user =$this->User->find('first', array('conditions' => array('User.id' => $user)));
        }

        $this->set('user', $this->Auth->user());
        $this->set('report_user', $report_user);
        $this->set('interval', array($this->beginOfDay, $this->endOfDay));
        $this->set('ticketsTotalCount', $ticketsTotalCount);
        $this->set('singleTicketStats', $singleTicketStats);
        $this->set('multiTicketStats', $multiTicketStats);
        $this->set('withdrawsOutcome', $withdrawsOutcome);

        $this->set('users', $users);
    }

    /**
     * Sportsbook daily report
     */
    public function admin_sportsbook_daily() {

        $registeredTotalCount = $this->User->find('count', array(
                'contain'   =>  array(),
                'conditions'    =>  array(
                    'User.registration_date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'User.group_id' =>  Group::USER_GROUP
                )
            )
        );

        $depositFundStats = $this->Deposit->find('first', array(
                'contain'   =>  array(),
                'fields'    =>  array(
                    'sum(Deposit.amount) AS total',
                    'count(Deposit.id) AS count'
                ),
                'conditions'    =>  array(
                    'Deposit.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Deposit.status'    =>  Deposit::DEPOSIT_STATUS_COMPLETED,
                    'Deposit.type'      =>  Deposit::DEPOSIT_TYPE_ONLINE
                )
            )
        );

        $withdrawChargeStats = $this->Withdraw->find('first', array(
                'contain'   =>  array(),
                'fields'    =>  array(
                    'sum(Withdraw.amount) AS total',
                    'count(Withdraw.id) AS count'
                ),
                'conditions'    =>  array(
                    'Withdraw.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Withdraw.status'    =>  Withdraw::WITHDRAW_STATUS_COMPLETED,
                    'Withdraw.type'      =>  Withdraw::WITHDRAW_TYPE_ONLINE
                )
            )
        );

        $ticketsTotalCount = $this->Ticket->find('count', array(
                'contain'   =>  array(),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay)
                )
            )
        );

        $singleTicketsCount = $this->Ticket->find('count', array(
                'contain'   =>  array(),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.type'   =>  Ticket::TICKET_TYPE_SINGLE
                )
            )
        );

        $multiTicketsCount = $this->Ticket->find('count', array(
                'contain'   =>  array(),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.type'   =>  Ticket::TICKET_TYPE_MULTI
                )
            )
        );

        $wonTicketsCount = $this->Ticket->find('count', array(
                'contain'   =>  array(),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.status'   =>  Ticket::TICKET_STATUS_WON
                )
            )
        );

        $lostTicketsCount = $this->Ticket->find('count', array(
                'contain'   =>  array(),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.status'   =>  Ticket::TICKET_STATUS_LOST
                )
            )
        );

        $pendingTicketsCount = $this->Ticket->find('count', array(
                'contain'   =>  array(),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.status'   =>  Ticket::TICKET_STATUS_PENDING
                )
            )
        );

        $cancelledTicketsCount = $this->Ticket->find('count', array(
                'contain'   =>  array(),
                'conditions'    =>  array(
                    'Ticket.date BETWEEN ? AND ?'  => array($this->beginOfDay, $this->endOfDay),
                    'Ticket.status'   =>  Ticket::TICKET_STATUS_CANCELLED
                )
            )
        );

        $this->set('chartsData', array(
            0   =>  array(
                'settings'  =>  array(
                    'id'            =>  0,
                    'title'         =>  __('Deposited / Withdrawed Amounts'),
                    'label_radius'  =>  75,
                    'radius'        =>  75
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label' =>  __('Deposited amount'),
                        'count' =>  $depositFundStats[0]['total'],
                        'radius'        =>  75
                    ),
                    1   =>  array(
                        'label' =>  __('Withdrawed amount'),
                        'count' =>  $withdrawChargeStats[0]['total'],
                        'radius'        =>  75
                    )
                )
            ),

            1   =>  array(
                'settings'  =>  array(
                    'id'            =>  1,
                    'title'         =>  __('Deposited / Withdrawed'),
                    'label_radius'  =>  75,
                    'radius'        =>  75
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label' =>  __('Deposited'),
                        'count' =>  $depositFundStats[0]['count'],
                        'radius'        =>  75
                    ),
                    1   =>  array(
                        'label' =>  __('Withdrawed'),
                        'count' =>  $withdrawChargeStats[0]['count'],
                        'radius'        =>  75
                    )
                )
            )
        ));

        $this->set('chartsData1', array(
            0   =>  array(
                'settings'  =>  array(
                    'id'            =>  2,
                    'title'         =>  __('Ticket statuses'),
                    'label_radius'  =>  100,
                    'radius'        =>  75
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label'     =>  __('Won'),
                        'count'     =>  $wonTicketsCount,
                        'radius'    =>  75
                    ),
                    1   =>  array(
                        'label'     =>  __('Lost'),
                        'count'     =>  $lostTicketsCount,
                        'radius'    =>  75
                    ),
                    2   =>  array(
                        'label'     =>  __('Pending'),
                        'count'     =>  $pendingTicketsCount,
                        'radius'    =>  75
                    ),
                    3   =>  array(
                        'label'     =>  __('Cancelled'),
                        'count'     =>  $cancelledTicketsCount,
                        'radius'    =>  75
                    )
                )
            ),

            1   =>  array(
                'settings'  =>  array(
                    'id'            =>  3,
                    'label'         =>  __('Ticket types'),
                    'label_radius'  =>  100,
                    'radius'        =>  75
                ),
                'data'      =>  array(
                    0   =>  array(
                        'label'     =>  __('Single'),
                        'count'     =>  $singleTicketsCount,
                        'radius'    =>  75
                    ),
                    1   =>  array(
                        'label'     =>  __('Multi'),
                        'count'     =>  $multiTicketsCount,
                        'radius'    =>  75
                    )
                )
            )
        ));

        if(isset($this->request->data['Report']['from']) || isset($this->request->data['Report']['to'])) {
            $this->layout = 'empty';
            $this->set('show', true);
        }else{
            $this->set('show', false);
        }

        $this->set('user', $this->Auth->user());
        $this->set('interval', array($this->beginOfDay, $this->endOfDay));

        $this->set('registeredTotalCount', $registeredTotalCount);
        $this->set('depositFundStats', $depositFundStats);
        $this->set('withdrawChargeStats', $withdrawChargeStats);

        $this->set('ticketsTotalCount', $ticketsTotalCount);
        $this->set('singleTicketsCount', $singleTicketsCount);
        $this->set('multiTicketsCount', $multiTicketsCount);

        $this->set('wonTicketsCount', $wonTicketsCount);
        $this->set('lostTicketsCount', $lostTicketsCount);
        $this->set('pendingTicketsCount', $pendingTicketsCount);
        $this->set('cancelledTicketsCount', $cancelledTicketsCount);
    }

    /**
     * Admin financial report
     */
    public function admin_financial_report() {

        $Finances = $this->User->find('all', array(
            'fields'        =>  array(
                'User.id',
                'User.username',
                'Group.name'
            ),
            'contain'       =>  array(
                'Group'         =>  array(
                    'fields'    =>  'Group.name'
                ),
                'Deposit'   =>  array(
                    'fields'    =>  'Deposit.amount',
                    'conditions'    =>  array(
                        'Deposit.type'  =>  $this->Deposit->getDepositType('DEPOSIT_TYPE_OFFLINE')
                    )
                ),
                'Withdraw'      =>  array(
                    'fields'        =>  'Withdraw.amount',
                    'conditions'    =>  array(
                        'Withdraw.type'  =>  $this->Withdraw->getWithdrawType('WITHDRAW_TYPE_OFFLINE')
                    )
                )
            ),
            'conditions'    =>  array(
                'User.group_id' =>  array(Group::OPERATOR_GROUP, Group::CASHIER_GROUP)
            )
        ));

        foreach($Finances AS $index => $Finance) {
            if(empty($Finance['Deposit'])) {
                $Finances[$index]['Deposit'] = array('total' => 0);
            }else{
                $DepositAmount = 0;
                foreach($Finance['Deposit'] AS $Deposit) {
                    $DepositAmount += $Deposit['amount'];
                }
                $Finances[$index]['Deposit'] = array('total' => $DepositAmount);
            }

            if(empty($Finance['Withdraw'])) {
                $Finances[$index]['Withdraw'] = array('total' => 0);
            }else{
                $WithdrawAmount = 0;
                foreach($Finance['Withdraw'] AS $Withdraw) {
                    $WithdrawAmount += $Withdraw['amount'];
                }
                $Finances[$index]['Withdraw'] = array('total' => $WithdrawAmount);
            }

            if($Finances[$index]['Deposit']['total'] == 0 && $Finances[$index]['Withdraw']['total'] == 0) {
                unset($Finances[$index]);
            }
        }

        $totalOutcome = array_sum( array_map( function($array) { return($array['Deposit']['total']); }, $Finances) );
        $totalIncome  = array_sum( array_map( function($array) { return($array['Withdraw']['total']); }, $Finances) );

        $this->set('user', $this->Auth->user());
        $this->set('interval', array($this->beginOfDay, $this->endOfDay));

        $this->set('Finances', $Finances);

        $this->set('totalIncome', $totalIncome);
        $this->set('totalOutcome', $totalOutcome);

        if(isset($this->request->data['Report']['from']) || isset($this->request->data['Report']['to'])) {
            $this->layout = 'empty';
            $this->set('show', true);
        }else{
            $this->set('show', false);
        }
    }
}