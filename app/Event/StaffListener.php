<?php
 /**
 * Staff Event Listener
 *
 * Holds Staff Event Listener Methods
 *
 * @package    Users
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('CakeEventListener', 'Event');

class StaffListener implements CakeEventListener
{
    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return array(
            'Controller.Staffs.add'             =>  'onStaffsAdd',
            'Controller.Staffs.edit'            =>  'onStaffsEdit',
            'Controller.Staffs.save'            =>  'onStaffsSave',
            'Controller.Staffs.beforeValidates' =>  'onBeforeValidates'
        );
    }

    /**
     * Initializes settings, etc
     * Event fired on admin staff add
     *
     * @param CakeEvent $event - Event Object
     *
     * @return void
     */
    public function onStaffsAdd(CakeEvent $event)
    {
        try {
            $currentUser    =   $event->subject()->Auth->user();
            $StaffUser      =   $event->subject()->Staff->getItem($event->data["dataId"]);

            if (!is_array($currentUser) || empty($currentUser)) {
                throw new Exception("User not logged?", 500);
            }

            if (!is_array($StaffUser) || empty($StaffUser)) {
                throw new Exception("Staff not exists?", 500);
            }

            switch ($StaffUser["Staff"]["group_id"]) {
                case Group::BRANCH_GROUP:
                case Group::AGENT_GROUP:
                    switch ($currentUser["group_id"])
                    {
                        case Group::ADMINISTRATOR_GROUP:
                        case Group::BRANCH_GROUP:
                            $Staff["Staff"]["referal_id"] = $currentUser["id"];
                            $event->subject()->Staff->save($Staff);
                            break;
                    }
                    break;
            }

            switch ($StaffUser["Staff"]["group_id"]) {
                case Group::BRANCH_GROUP:
                    // get default
                    break;
                case Group::AGENT_GROUP:

                    $settings = $event->subject()->Staff->UserSettings->getDefaultSettingsByGroup(Group::AGENT_GROUP);

                    foreach ($settings AS $settingKey => $value) {
                        $setting_array = explode('.', $settingKey);
                        foreach ($currentUser["UserSettings"] as $branchSetting) {
                            if (end($setting_array) == $branchSetting["key"]) {
                                $settings[$settingKey] = $branchSetting["value"];
                            }
                        }
                    }

                    foreach ($settings AS $setting => $value) {
                        $setting_array = explode('.', $setting);
                        $event->subject()->Staff->UserSettings->create();
                        $event->subject()->Staff->UserSettings->save(array("UserSettings" => array(
                            "user_id"   =>  $StaffUser["Staff"]["id"],
                            "model"     =>  "Staff",
                            "key"       =>  end($setting_array),
                            "value"     =>  $value
                        )));
                    }
                    break;
            }

        }catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }

    /**
     * Initializes settings, etc
     * Event fired on admin staff edit
     *
     * @param CakeEvent $event - Event Object
     *
     * @return void
     */
    public function onStaffsEdit(CakeEvent $event)
    {
        try {

            if (empty($event->data["itemId"])) {
                throw new Exception("Unknown staff id?", 500);
            }

            if (empty($event->data["itemData"])) {
                throw new Exception("Unknown staff data?", 500);
            }

            $event->data["itemData"] =  $event->subject()->Staff->getItem($event->data["itemId"], array('UserSettings'));

            switch ($event->data["itemData"]["Staff"]["group_id"]) {
                case Group::BRANCH_GROUP:
                case Group::AGENT_GROUP && ( CakeSession::read('Auth.User.group_id') == Group::BRANCH_GROUP ) && ( $event->data["itemData"]["Staff"]["referal_id"] == CakeSession::read('Auth.User.id') ) :

                    $appendSettings     =   array();
                    $staffFields        =   $event->subject()->Staff->getEdit($event->subject()->request->data);
                    $settingsModel      =   $event->subject()->Staff->UserSettings->name;
                    $defaultSettings    =   $event->subject()->Staff->UserSettings->getDefaultSettingsByGroup($event->data["itemData"]["Staff"]["group_id"]);
                    $currentSettings    =   $event->data["itemData"]["UserSettings"];

                    foreach ($currentSettings AS $currentSetting ) {
                        $settingKey = $settingsModel . '.' . $currentSetting["model"] . '.' . $currentSetting["key"];
                        $appendSettings[$settingKey] = array("value" => $currentSetting["value"], "placeholder" => __("Unlimited"));
                    }

                    foreach ($defaultSettings AS $settingKey => $defaultSetting) {
                        $defaultSettings[$settingKey] = array("value" => $defaultSetting, "placeholder" => __("Unlimited"));
                    }

                    $event->subject()->Staff->setEdit(array_merge($staffFields, array_merge($defaultSettings, $appendSettings)));
                    break;
            }

        } catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }

    public function onStaffsSave(CakeEvent $event)
    {
        if (empty($event->data["itemId"])) {
            throw new Exception("Unknown staff id?", 500);
        }

        if (empty($event->data["itemData"])) {
            throw new Exception("Unknown staff data?", 500);
        }

        $event->data["itemData"] =  $event->subject()->Staff->getItem($event->data["itemId"], array('UserSettings'));

        switch ($event->data["itemData"]["Staff"]["group_id"]) {
            case Group::BRANCH_GROUP:
            case Group::AGENT_GROUP && ( CakeSession::read('Auth.User.group_id') == Group::BRANCH_GROUP ) && ( $event->data["itemData"]["Staff"]["referal_id"] == CakeSession::read('Auth.User.id') ) :
                $appendSettings     =   array();
                $settingsModel      =   $event->subject()->Staff->UserSettings->name;
                $defaultSettings    =   $event->subject()->Staff->UserSettings->getDefaultSettingsByGroup($event->data["itemData"]["Staff"]["group_id"]);
                $currentSettings    =   $event->data["itemData"]["UserSettings"];
                $requestSettings    =   isset($event->subject()->request->data["UserSettings"]["Staff"]) ? $event->subject()->request->data["UserSettings"]["Staff"] : array();

                foreach ($currentSettings AS $currentSetting ) {
                    $settingKey = $settingsModel . '.' . $currentSetting["model"] . '.' . $currentSetting["key"];
                    $appendSettings[$settingKey] = array("value" => $currentSetting["value"]);
                }

                foreach ($defaultSettings AS $settingKey => $defaultSetting) {
                    $defaultSettings[$settingKey] = array("value" => $defaultSetting);
                }

                foreach ($requestSettings AS $settingKey => $requestSetting) {
                    $settingKey = $settingsModel . '.' . $event->subject()->Staff->name . '.' . $settingKey;
                    $appendSettings[$settingKey] = array("value" => $requestSetting);
                }

                foreach (array_merge($defaultSettings, $appendSettings) AS $staffSettingKey => $staffSettingValue) {
                    $replace = $settingsModel . '.' .$event->subject()->Staff->name . '.';
                    $event->subject()->Staff->UserSettings->setSetting(
                        $event->data["itemData"]["Staff"]["id"],
                        $event->subject()->Staff->name,
                        str_replace($replace, '', $staffSettingKey),
                        $staffSettingValue["value"]
                    );
                }

                break;
        }
    }

    public function onBeforeValidates(CakeEvent $event)
    {
        try {
            $scopeModel     =   $event->subject()->Staff->name;
            $currentUser    =   $event->subject()->Auth->user();
            $settingsModel  =   $event->subject()->Staff->UserSettings->name;

            if (!is_array($currentUser) || empty($currentUser)) {
                throw new Exception("Unknown user", 500);
            }

            switch ($currentUser["group_id"]) {
                case Group::BRANCH_GROUP:

                    $rule   =   "highest_number_of_agents";
                    $limit  =   $event->subject()->{$scopeModel}->{$settingsModel}->getSetting($rule);

                    if (!is_null($limit) && is_numeric($limit)) {
                        $event->subject()->request->data[$scopeModel][$rule] = $event->subject()->{$scopeModel}->find('count', array('conditions' => array(
                            "$scopeModel.referal_id"   =>  CakeSession::read('Auth.User.id'),
                            "$scopeModel.group_id"     =>  Group::AGENT_GROUP
                        )));

                        $event->subject()->{$scopeModel}->set($event->subject()->request->data);

                        $event->subject()->{$scopeModel}->validator()->add($rule, array(
                            'rule'    => array('comparison', '<', $limit),
                            'message' => __("The agent cannot be created. You have reach your limit. Please contact your service provider.")
                        ));
                    }

                    break;
            }
        } catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }
}