<?php
/**
 * Log Model
 *
 * Handles Log Data Source Actions
 *
 * @package    Logs.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class UserSettings extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'UserSettings';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'users_settings';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'big',
            'length'    => 22,
            'null'      => false
        ),
        'user_id'   => array(
            'type'      => 'big',
            'length'    => 22,
            'null'      => false
        ),
        'group_id'  => array(
            'type'      => 'big',
            'length'    => 22,
            'null'      => false
        ),
        'key'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'value'     => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        )
    );

/*    public $validate = array(
        'highest_number_of_agents'      =>  array(
            'required'      =>  true,
            'allowEmpty'    =>  false,
            'rule'          =>  null,
            'message'       =>  'You have reached maximum highest number of agents'
        ),

        'highest_number_of_users'       =>  array(
            'required'      =>  true,
            'allowEmpty'    =>  false,
            'rule'          =>  'validateHighestNumberOfUsers',
            'message'       =>  'You have reached maximum highest number of agent user'
        ),

        'highest_stake_of_agents_users' =>  array(
            'required'      =>  true,
            'allowEmpty'    =>  false,
            'rule'          =>  'validateHighestStakeOfAgentsUsers',
            'message'       =>  'You have reached maximum highest stake of agent user'
        ),

        'lowest_stake_of_agents_users'  =>  array(
            'required'      =>  true,
            'allowEmpty'    =>  false,
            'rule'          =>  'validateLowestStakeOfAgentsUsers',
            'message'       =>  'You have reached maximum lowest stake of agent user'
        ),

        'highest_winning_amount_of_agents_users'    =>  array(
            'required'      =>  true,
            'allowEmpty'    =>  false,
            'rule'          =>  'validateHighestWinningAmountOfAgentsUsers',
            'message'       =>  'You have reached maximum wining amount of agent user'
        )
    );*/

    /**
     * @param $userId
     * @param $model
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function setSetting($userId, $model, $key, $value)
    {
        $UserSettings = $this->find('first', array(
            'conditions'    =>  array(
                'UserSettings.user_id'   =>  $userId,
                'UserSettings.model'     =>  $model,
                'UserSettings.key'       =>  $key
            )
        ));

        if (!empty($UserSettings)) {
            $this->id = $UserSettings[$this->name]["id"];
            $UserSettings[$this->name]["value"] = $value;
            $this->save($UserSettings);
        } else {
            $this->create();
            $this->save(array(
                    $this->name => array(
                        'user_id'   =>  $userId,
                        'model'     =>  $model,
                        'key'       =>  $key,
                        'value'     =>  $value,
                    ))
            );
        }
    }

    /**
     * @param      $key
     * @param null $userId
     *
     * @return string|null
     */
    public  function getSetting($key, $userId = null, $group = null)
    {
        if (is_null($group)) {
            $group = CakeSession::read('Auth.User.group_id');
        }

        $UserSettings = array();

        foreach ($this->getDefaultSettingsByGroup($group) AS $setting => $value) {
            $setting_array = explode('.', $setting);
            $UserSettings[] = array(
                "value" =>  $value,
                "key"   =>  end($setting_array)
            );
        }

        foreach ($UserSettings AS $i => $defaultSetting) {
            foreach (CakeSession::read('Auth.User.UserSettings') AS $setting) {
                if ($defaultSetting["key"] == $setting["key"]) {
                    $UserSettings[$i] = $setting;
                }
            }
        }

        if (is_null($userId) && empty($UserSettings)) {
            // exception
        }

        if (!is_null($userId)) {
            $UserSettings   =   array();

            $data = $this->find('all', array('conditions' => array(
                'user_id'    =>  $userId
            )));

            foreach($data AS $setting) {
                $UserSettings[] = $setting["UserSettings"];
            }
        }

        if (is_array($UserSettings) && !empty($UserSettings)) {
            foreach ($UserSettings AS $UserSetting) {
                if ($UserSetting["key"] == $key) {
                    return $UserSetting["value"];
                }
            }
        }

        return null;
    }

    /**
     * @param $groupId
     *
     * @return array
     */
    public function getDefaultSettingsByGroup($groupId)
    {
        $settings = array(
            // Branch
            Group::BRANCH_GROUP  =>  array(
                "UserSettings.Staff.highest_number_of_agents"                  =>  null,
                "UserSettings.Staff.highest_number_of_users"                   =>  null,
                "UserSettings.Staff.highest_stake_of_agents_users"             =>  Configure::read("Settings.maxBet"),
                "UserSettings.Staff.lowest_stake_of_agents_users"              =>  Configure::read("Settings.minBet"),
                "UserSettings.Staff.highest_winning_amount_of_agents_users"    =>  Configure::read("Settings.maxWin")
            ),

            Group::AGENT_GROUP  =>  array(
                "UserSettings.Staff.highest_stake_of_agents_users"             =>  Configure::read("Settings.maxBet"),
                "UserSettings.Staff.lowest_stake_of_agents_users"              =>  Configure::read("Settings.minBet"),
                "UserSettings.Staff.highest_winning_amount_of_agents_users"    =>  Configure::read("Settings.maxWin")
            )
        );

        if (!isset($settings[$groupId])) {
            return array();
        }

        return $settings[$groupId];
    }

    public function validateHighestNumberOfAgents($field=array(), $compare_field=null)
    {

    }

    public function validateHighestNumberOfUsers($field=array(), $compare_field=null)
    {
        return false;
    }

    public function validateHighestStakeOfAgentsUsers($field=array(), $compare_field=null)
    {
        return false;
    }

    public function validateLowestStakeOfAgentsUsers($field=array(), $compare_field=null)
    {
        return false;
    }

    public function validateHighestWinningAmountOfAgentsUsers($field=array(), $compare_field=null)
    {
        return false;
    }
}