<?php
 /**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::import('Xml');
App::import('Core', 'Controller');
App::uses('Controller', 'Controller');
App::uses('Component', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('TimeZoneComponent', 'Controller/Component');

App::uses('CakeEvent', 'Event');
App::uses('CakeEventManager', 'Event');
App::uses('SettingsListener', 'Event');
App::uses('OddsServiceListener', 'Feeds.Event');

App::uses('Setting', 'Model');


App::uses('FeedShell', 'Feeds.Console');

class FeedAppShell extends AppShell implements FeedShell
{
    /**
     * Feed Provider For Shell
     *
     * @var null
     */
    private $FeedProvider = null;

    /**
     * Initializes the Shell
     */
    public function initialize()
    {
        $this->FeedProvider = 'Feeds.' . Configure::read('feeds.enabled.feed.name');
    }

    /**
     * Method which implements countries importing / updating
     *
     * @return mixed
     */
    public function importCountries()
    {
        try {
            $this->dispatchShell($this->FeedProvider . ' importCountries');
        }catch (Exception $e) {
            CakeLog::write('feed', $e);
        }
    }

    /**
     * Method which implements sports importing / updating
     *
     * @return mixed
     */
    public function importSports()
    {
        try {
            $this->dispatchShell($this->FeedProvider . ' importSports');
        }catch (Exception $e) {
            CakeLog::write('feed', $e);
        }
    }

    /**
     * Method which implements leagues importing / updating
     *
     * @return mixed
     */
    public function importLeagues()
    {
        try {
            $this->dispatchShell($this->FeedProvider . ' importLeagues');
        }catch (Exception $e) {
            CakeLog::write('feed', $e);
        }
    }

    /**
     * Method which implements events importing / updating
     *
     * @return mixed
     */
    public function importEvents()
    {
        try {
            $this->dispatchShell($this->FeedProvider . ' importEvents');
        }catch (Exception $e) {
            CakeLog::write('feed', $e);
        }
    }

    /**
     *
     */
    public function updateEvents()
    {
        try {
            $this->dispatchShell($this->FeedProvider . ' updateEvents ' .  implode(' ', $this->args));
        }catch (Exception $e) {
            CakeLog::write('feed', $e);
        }
    }
}