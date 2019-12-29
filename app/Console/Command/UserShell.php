<?php
 /**
 * Sports Shell
 *
 * Handles Sports Shell Tasks
 *
 * @package    Sports
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */ 

class UserShell extends Shell
{
    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('User', 'Group', 'Setting');

    /**
     * Updates Leagues activity statuses
     *
     * @return void
     */
    public function weeklyBalance()
    {
        ini_set('memory_limit', '-1');

        foreach ($this->Setting->find('all') AS $Setting) {
            if (isset($Setting['Setting']['key']) && isset($Setting['Setting']['value'])) {
                Configure::Write('Settings.' . $Setting['Setting']['key'], $Setting['Setting']['value']);
            }
        }

        if (Configure::Read('Settings.weekly_balance_reset') && Configure::Read('Settings.weekly_balance_reset_day') == date("N")) {
            $Users = $this->User->find('all', array(
                "contain"      =>  array(),
                'conditions'   =>  array(
                    'User.group_id' =>  Group::AGENT_GROUP
                )
            ));

            foreach ($Users AS $User) {
                $this->User->create();
                $User["User"]["balance"] = Configure::Read('Settings.weekly_balance_reset_balance');
                $this->User->save($User, false);
            }
        }
    }
}