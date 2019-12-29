<?php
 /**
 * User Settings Event Listener
 *
 * Holds User Settings Event Listener Methods
 *
 * @package    Settings
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('CakeEventListener', 'Event');

class UserSettingsListener implements CakeEventListener
{
    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents() {
        return array(
            // 'Controller.Tickets.setStake'    =>  'onSetStake'
        );
    }
}