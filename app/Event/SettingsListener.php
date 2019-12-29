<?php
 /**
 * Settings Event Listener
 *
 * Holds Settings Event Listener Methods
 *
 * @package    Settings
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('CakeEventListener', 'Event');

class SettingsListener implements CakeEventListener
{
    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents() {
        return array(
            'Controller.App.beforeFilter'    =>  'onBeforeFilter'
        );
    }

    /**
     * Initializes global settings
     * Event fired on page startup
     *
     * @param CakeEvent $event - CakeEvent Object
     *
     * @return void
     */
    public function onBeforeFilter(CakeEvent $event)
    {
        try {
            if (Configure::Read('Settings.initialized') == null && PHP_SAPI != 'cli') {

                // Default settings
                foreach ($event->subject()->Setting->find('all') AS $Setting) {
                    if (isset($Setting['Setting']['key']) && isset($Setting['Setting']['value'])) {
                        Configure::Write('Settings.' . $Setting['Setting']['key'], $Setting['Setting']['value']);
                    }
                }

                $Currencies = $event->subject()->Currency->getCodesList();

                Configure::write('Currencies', $Currencies);
                Configure::Write('Settings.currency', $Currencies[Configure::Read('Settings.defaultCurrency')]);

                $currentUser    =   $event->subject()->Auth->user();

                switch ($currentUser["group_id"]) {
                    case $currentUser["group_id"] == Group::USER_GROUP:

                        if (is_numeric($currentUser["min_stake"]) && $currentUser["min_stake"] > 0) {
                            Configure::write("Settings.minBet", $currentUser["min_stake"]);
                        }

                        if (is_numeric($currentUser["max_stake"]) && $currentUser["max_stake"] > 0) {
                            Configure::write("Settings.maxBet", $currentUser["max_stake"]);
                        }

                        if (is_numeric($currentUser["max_winning"]) && $currentUser["max_winning"] > 0) {
                            Configure::write("Settings.maxWining", $currentUser["max_winning"]);
                        }

                        break;
                    case $currentUser["group_id"] == Group::USER_GROUP && ( int) $currentUser["referal_id"] > 0:
                        $agentUser = $event->subject()->User->find('first', array(
                                "contain"   =>  array("UserSettings"),
                                "conditions" => array(
                                    'User.id' => CakeSession::read("Auth.User.referal_id"),
                                    'User.group_id' => Group::AGENT_GROUP)
                            )
                        );

                        if (!empty($agentUser) && isset($agentUser["UserSettings"]) && !empty($agentUser["UserSettings"]) && is_array($agentUser["UserSettings"])) {
                            $minBet = $event->subject()->User->UserSettings->getSetting('lowest_stake_of_agents_users', $agentUser["User"]["id"]);
                            $maxBet = $event->subject()->User->UserSettings->getSetting('highest_stake_of_agents_users', $agentUser["User"]["id"]);
                            $maxWining = $event->subject()->User->UserSettings->getSetting('highest_winning_amount_of_agents_users', $agentUser["User"]["id"]);

                            if (!is_null($minBet)) {
                                Configure::write("Settings.minBet", $minBet);
                            }

                            if (!is_null($maxBet)) {
                                Configure::write("Settings.maxBet", $maxBet);
                            }

                            if (!is_null($maxWining)) {
                                Configure::write("Settings.maxWining", $maxWining);
                            }
                        }

                        break;
                }

                Configure::Write('Settings.initialized', 1);
            }
        }catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }
}