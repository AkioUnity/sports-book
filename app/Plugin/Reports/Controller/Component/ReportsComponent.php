<?php
/**
 * Reports Component
 *
 * This is Common Reports Component Class
 * Class provides reusable bits of controller logic that can be composed into another controllers.
 * Class provides common methods for reports handling
 *
 * @package     Reports.Controller
 * @author      Deividas Petraitis <deividas@laiskai.lt>
 * @copyright   2013 The ChalkPro Betting Scripts
 * @license     http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version     Release: @package_version@
 * @link        http://www.chalkpro.com/
 * @see         Controller::$components
 */

class ReportsComponent extends Component
{
    public $components = array('Auth');

    /**
     * User Model Object
     *
     * @var User $User
     */
    public $User = null;

    /**
     * Constructor
     *
     * @param ComponentCollection $collection
     * @param array $settings
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        $this->User = ClassRegistry::init('User');
        parent::__construct($collection, $settings);
    }

    /**
     * Generates Report
     *
     * @param $data
     * @param $model
     * @param $from
     * @param $to
     * @return array
     */
    public function downloadReport($data, $model, $from, $to)
    {
        $filename = $model . "_" . $from . '-' . $to . '.csv';
        $csv_file = fopen('php://output', 'w');

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($csv_file, array($from, $to), ',', '"');
        $header_row = $data['header'];
        fputcsv($csv_file, $header_row, ',', '"');

        foreach ($data as $key => $dataRow) {
            if ($key !== 'header') {

                $row = array();
                switch ($model) {
                    case 'User':
                        $row = array(
                            $dataRow['User']['id'],
                            $dataRow['User']['registration_date'],
                            $dataRow['User']['username'],
                            $dataRow['User']['balance'],
                            $dataRow['User']['first_name'],
                            $dataRow['User']['last_name'],
                            $dataRow['User']['address1'],
                            $dataRow['User']['address2'],
                            $dataRow['User']['zip_code'],
                            $dataRow['User']['city'],
                            $dataRow['User']['country'],
                            $dataRow['User']['email'],
                            $dataRow['User']['mobile_number'],
                            $dataRow['User']['date_of_birth']
                        );
                        break;

                    case 'Ticket':
                        $row = array(
                            $dataRow['Ticket']['id'],
                            $dataRow['Ticket']['user_id'],
                            $dataRow['Ticket']['date'],
                            $dataRow['Ticket']['type'],
                            $dataRow['Ticket']['events_count'],
                            $dataRow['Ticket']['amount'],
                            $dataRow['Ticket']['odd'],
                            $dataRow['Ticket']['return']
                        );
                        break;

                    case 'Deposit':
                        $row = array(
                            $dataRow['Deposit']['id'],
                            $dataRow['Deposit']['user_id'],
                            $dataRow['User']['username'],
                            $dataRow['Deposit']['date'],
                            $dataRow['Deposit']['type'],
                            $dataRow['Deposit']['amount']
                        );
                        break;

                    case 'Withdraw':
                        $row = array(
                            $dataRow['Withdraw']['id'],
                            $dataRow['Withdraw']['user_id'],
                            $dataRow['User']['username'],
                            $dataRow['User']['first_name'] . ' ' . $dataRow['User']['last_name'],
                            $dataRow['User']['bank_name'],
                            $dataRow['User']['bank_code'],
                            $dataRow['User']['account_number'],
                            $dataRow['Withdraw']['date'],
                            $dataRow['Withdraw']['type'],
                            $dataRow['Withdraw']['amount']
                        );
                        break;
                    default:
                        break;
                }
                fputcsv($csv_file, $row, ',', '"');
            }
        }
        fclose($csv_file);
        die;
    }

    /**
     * Returns users list for select box
     *
     * @param $UsersGroup
     * @return array
     */
    public function usersSelection($UsersGroup) {
        if($this->Auth->user('Group.id') != Group::ADMINISTRATOR_GROUP) {
            $users      = array(0 => array('User' => array('id' => $this->Auth->user('id'), 'username' => $this->Auth->user('username'))));
        }else{
            $users      = array_merge(array(0 => array('User' => array('id' => '0', 'username' => __('All operators')))), $this->User->getUsersByGroup($UsersGroup));
        }

        $usersMap = array_map(function($user){ return array('id' => $user['User']['id'], 'username' => $user['User']['username']); }, $users);
        $users = array();

        foreach($usersMap AS $user) {
            $users[$user['id']] = $user['username'];
        }

        return $users;
    }
}