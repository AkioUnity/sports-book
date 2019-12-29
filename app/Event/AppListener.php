<?php
 /**
 * App Event Listener
 *
 * Holds App Event Listener Methods
 *
 * @package    Users
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('CakeEventListener', 'Event');

class AppListener implements CakeEventListener
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
            'Controller.App.beforeFilter'    =>  'onBeforeFilter'
        );
    }

    /**
     * Initializes settings, etc
     * Event fired on page startup
     *
     * @param CakeEvent $event - Event Object
     *
     * @return void
     */
    public function onBeforeFilter(CakeEvent $event)
    {
        try {
            $event->subject()->Group->init();

            /** TODO: to view listener? */
            if (isset($event->subject()->params) && $event->subject()->params['admin'] == 1)
            {
                /** TODO: from app controller / global config? */
                $event->subject()->theme = 'Admin';

                /** TODO: external auth listener? */
                $event->subject()->Auth->loginRedirect = array('prefix' => 'admin', 'plugin' => null, 'controller' => 'users', 'action' => 'logout');
                $event->subject()->Auth->logoutRedirect = array('prefix' => 'admin', 'plugin' => null, 'controller' => 'users', 'action' => 'logout');
                $event->subject()->Auth->autoRedirect = false;

                if ($event->subject()->Session->read('Auth.User.id')) {
                    if ($event->subject()->Session->read('Auth.User.group_id') == Group::USER_GROUP) {
                        $event->subject()->redirect($event->subject()->Auth->logout());
                    }
                }
            }
            else {
                $event->subject()->theme = 'Design';

                if (isset($_GET["theme"])) {
                    $event->subject()->theme = 'Redesign';
                }

                if((isset($_SERVER['SERVER_NAME']))) {
                    if(strtolower($_SERVER['SERVER_NAME']) == "apostas24horas.com") {
                        $event->subject()->theme = 'ChalkPro';
                    }
                }
                $event->subject()->set('bets', $event->subject()->Ticket->getBetsFromSession());
                $event->subject()->set('getNews', $event->subject()->News->getNews());
            }

            $theme = $event->subject()->theme;

            if (in_array($event->subject()->theme, array("Redesign", "Design"))) {
                $event->subject()->layout = 'home';
            }

            $themeWebRoot = App::themePath($theme) . WEBROOT_DIR;
            $appWebRoot = WWW_ROOT . 'theme' . DS . $theme;

            if (!file_exists($appWebRoot) && PHP_SAPI != 'cli') {
                @symlink($themeWebRoot, $appWebRoot);
            }
        } catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }
}