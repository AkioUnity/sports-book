<?php
/**
 * Front Risks Controller
 *
 * Handles Risks Actions
 *
 * @package    Risks
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class RisksController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Risks';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Risk', 'Setting', 'Sport', 'League', 'Ticket', 'Deposit', 'Withdraw');

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
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'riskSettings')) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('can\'t save settings.', true), 'error');
            }
        }

        $settings = $this->Setting->getRiskSettings();

        switch ($this->Auth->user('group_id')) {
            case Group::BRANCH_GROUP:
                $settings["highest_number_of_agents"]["value"] = $this->User->UserSettings->getSetting('highest_number_of_agents');
                $settings["highest_number_of_users"]["value"] = $this->User->UserSettings->getSetting('highest_number_of_users');
                $settings["maxBet"]["value"] = $this->User->UserSettings->getSetting('highest_stake_of_agents_users');
                $settings["minBet"]["value"] = $this->User->UserSettings->getSetting('lowest_stake_of_agents_users');
                $settings["maxWin"]["value"] = $this->User->UserSettings->getSetting('highest_winning_amount_of_agents_users');
                $view = "admin_index/branch_admin_index";
                break;
            case Group::AGENT_GROUP:
                $settings["maxBet"]["value"] = $this->User->UserSettings->getSetting('highest_stake_of_agents_users', $this->Auth->user('referal_id'));
                $settings["minBet"]["value"] = $this->User->UserSettings->getSetting('lowest_stake_of_agents_users', $this->Auth->user('referal_id'));
                $settings["maxWin"]["value"] = $this->User->UserSettings->getSetting('highest_winning_amount_of_agents_users', $this->Auth->user('referal_id'));
                $view = "admin_index/agent_admin_index";
                break;
            default:
                $view = "admin_index";
                break;
        }

        $this->set('settings', $settings);
        $this->set('tabs', $this->Risk->getTabs($this->request->params));

        return $this->render($view);
    }

    function admin_sports() {
        if (!empty($this->request->data)) {
            //save
//            if ($this->Sport->updateRisk($this->request->data)) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
//            } else {
//                $this->Admin->setMessage(__('can\'t save settings.', true), 'success');
//            }
        }

        $this->Paginator->settings  =   array(
            $this->Sport->name => array(
                'limit' => Configure::read('Settings.itemsPerPage')
            )
        );

        $data = $this->Paginator->paginate( $this->Sport->name );

        $this->set('data', $data);
        $this->set('tabs', $this->Risk->getTabs($this->params->params));
    }

    function admin_leagues($sportId = null) {
        if (!empty($this->request->data)) {
            //save
            if ($this->League->updateRisk($this->request->data)) {
                $this->Admin->setMessage(__('Settings saved.', true), 'success');
            } else {
                $this->Admin->setMessage(__('Error, cannot save settings.', true), 'error');
            }
        }

        $this->Paginator->settings  =   array(
            $this->League->name => array(
                'limit' => Configure::read('Settings.itemsPerPage')
            )
        );

        if (isset($sportId)) {
            $this->Paginator->settings[$this->League->name]['conditions'] = array(
                'League.sport_id' => $sportId
            );
        }

        $data = $this->Paginator->paginate( $this->League->name );

        $this->set('data', $data);
        $this->set('sports', $this->Sport->getList());
        $this->set('tabs', $this->Risk->getTabs($this->params->params));
    }
    
    public function admin_warnings()
    {
        $bigOddTickets = $this->Ticket->getTicketsByOdd(Configure::read('Settings.bigOdd'));
        $bigStakeTickets = $this->Ticket->getBigStakeTickets(Configure::read('Settings.bigStake'));
        $bigWinningTickets = $this->Ticket->getBigWinningTickets(Configure::read('Settings.bigWinning'));
        $bigDeposits = $this->Deposit->getDepositsByAmount(Configure::read('Settings.bigDeposit'));
        $bigWithdraws = $this->Withdraw->getBigWithdraws(Configure::read('Settings.bigWithdraw'));
        
        $this->set(compact('bigOddTickets', 'bigStakeTickets', 'bigWinningTickets', 'bigDeposits', 'bigWithdraws'));
        $this->set('tabs', $this->Risk->getTabs($this->params->params));
    }
}