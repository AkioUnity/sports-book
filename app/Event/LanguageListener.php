<?php
 /**
 * Language Event Listener
 *
 * Holds Language Event Listener Methods
 *
 * @package    Language
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('CakeEventListener', 'Event');

class LanguageListener implements CakeEventListener
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
     * Initializes settings, etc
     * Event fired on page startup
     *
     * @param CakeEvent $event - CakePHP Event Object
     *
     * @return void
     */
    public function onBeforeFilter(CakeEvent $event)
    {
        $availableLanguages = $event->subject()->Language->get();

        $newLanguage        =   null;
        $requestLanguage    =   $event->subject()->request->language;


        if (is_null($requestLanguage)) {

            $authUserLanguage   =   $event->subject()->Auth->user('Language');

            if (!is_null($authUserLanguage)) {
                $newLanguage = $authUserLanguage['name'];
            } else {
                $newLanguage = null;
                $defaultLanguage = Configure::read('Settings.defaultLanguage');
                array_map(function($language) USE ($defaultLanguage, &$newLanguage) {
                    if ($defaultLanguage == $language["id"]) {
                        $newLanguage = $language["name"];
                    }
                }, $availableLanguages);
            }
        } else {
            $newLanguage = $requestLanguage;
        }

        if (!isset($availableLanguages[$newLanguage])) {
            $newLanguage = Configure::read('Config.language');
        }

        if (Configure::read('Config.language') != $newLanguage) {
            $newLanguage = $availableLanguages[$newLanguage];
            Configure::write('Config.language', $newLanguage['name']);
        }

        Configure::write('Config.languages', $availableLanguages);
    }
}