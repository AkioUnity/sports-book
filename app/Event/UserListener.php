<?php
 /**
 * Users Event Listener
 *
 * Holds Users Event Listener Methods
 *
 * @package    Users
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('UserSettings', 'Model');
App::uses('CakeEventListener', 'Event');

class UserListener implements CakeEventListener
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
            'Controller.App.beforeFilter'       =>  'onBeforeFilter',
            'Controller.Users.login'            =>  'onUserLogin',
            'Controller.Users.add'              =>  'onUsersAdd',
            'Controller.Users.beforeValidates'  =>  'onBeforeValidates'
        );
    }

    /**
     * Initializes users settings
     * Event fired on page startup
     *
     * @param CakeEvent $event - Event Object
     *
     * @return void
     */
    public function onBeforeFilter(CakeEvent $event)
    {
        if (isset($_GET["r"]) && is_numeric($_GET["r"])) {
            CakeSession::write('referral', intval($_GET["r"]));
        }

        try {
            if ($event->subject()->Auth->loggedIn())
            {
                $UserId     = $event->subject()->Auth->user('id');
                $UserData   = $event->subject()->User->getItem($UserId, array(
                    0   =>  'Group',
                    1   =>  'Language',
                    2   =>  'UserSettings'
                ));

                if (empty($UserData)) {
                    $event->subject()->Auth->logout();
                } else {
                    $User = $UserData['User'];
                    unset($UserData['User']);

                    CakeSession::write(AuthComponent::$sessionKey, array_merge($User, $UserData));

                    $event->subject()->User->updateLastVisit($UserId);
                }
            } else {

                $role_data = $event->subject()->Group->read(null, Group::GUEST_GROUP);

                $aro_node = $event->subject()->Aro->node($role_data);

                $request = $event->subject()->request;

                $location = ( implode( '/', array_filter( array( 'controllers', $request->params['plugin'], $request->params['controller'], $request->params['action']) ) ) );

                $aco_node = $event->subject()->Aco->node($location);

                if (!empty($aco_node) && !empty($aro_node))
                {
                    if ($event->subject()->Acl->check($role_data,  $location))
                    {
                        $actions = array_reverse( explode('/', $location) );

                        while (!empty($actions)) {
                            $event->subject()->Auth->allow( strtolower( implode('/', array_reverse( $actions )) ) );
                            array_pop($actions);
                        }
                    }
                }
            }
        }catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }

    /**
     * Event fired after user successfully logged in
     *
     * @param CakeEvent $event - Event Object
     *
     * @return void
     */
    public function onUserLogin(CakeEvent $event)
    {

    }

    /**
     * Initializes settings, etc
     * Event fired on admin staff add
     *
     * @param CakeEvent $event - Event Object
     *
     * @return void
     */
    public function onUsersAdd(CakeEvent $event)
    {
        try {
            $currentUser    =   $event->subject()->Auth->user();
            $createdUser    =   $event->subject()->User->getItem($event->data["dataId"]);

            if (!is_array($currentUser) || empty($currentUser)) {
                throw new Exception("User not logged?", 500);
            }

            if (!is_array($createdUser) || empty($createdUser)) {
                throw new Exception("Staff not exists?", 500);
            }

            switch ($currentUser["group_id"]) {
                case Group::AGENT_GROUP:
                    $createdUser["User"]["referal_id"] = $currentUser["id"];
                    $event->subject()->User->save($createdUser);
                    break;
            }

        }catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }

    public function onBeforeValidates(CakeEvent $event)
    {
        $scopeModel     =   $event->subject()->User->name;
        $currentUser    =   $event->subject()->Auth->user();
        $settingsModel  =   $event->subject()->User->UserSettings->name;

        if (!is_array($currentUser) || empty($currentUser)) {
            throw new Exception("Unknown user", 500);
        }

        switch ($currentUser["group_id"]) {
            case Group::AGENT_GROUP:

                $rule   =   'highest_number_of_users';
                $limit  =   $event->subject()->{$scopeModel}->{$settingsModel}->getSetting($rule, $currentUser['id']);

                if (is_null($limit)) {
                    $limit  =   $event->subject()->{$scopeModel}->{$settingsModel}->getSetting($rule, $currentUser['referal_id']);
                }

                if (!is_null($limit) && is_numeric($limit)) {
                    $event->subject()->request->data[$scopeModel][$rule] = $event->subject()->{$scopeModel}->find('count', array('conditions' => array(
                        "$scopeModel.referal_id"   =>  CakeSession::read('Auth.User.id'),
                        "$scopeModel.group_id"     =>  Group::USER_GROUP
                    )));

                    $event->subject()->{$scopeModel}->set($event->subject()->request->data);

                    $event->subject()->{$scopeModel}->validator()->add($rule, array(
                        'rule'    => array('comparison', '<', $limit),
                        'message' => __("The user cannot be created. You have reach your limit. Please contact your service provider.")
                    ));
                }

                break;
        }
    }
}