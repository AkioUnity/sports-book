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

class CreditPaymentSettingsListener implements CakeEventListener
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
            if (Configure::Read('Settings.initialized') == 1 && PHP_SAPI != 'cli') {
                if (!is_null($event->subject()->Auth)) {
                    $currentUser    =   $event->subject()->Auth->user();
                    $credit         =   $event->subject()->CreditPayment->getCredit($currentUser["id"]);

                    $minBet = Configure::read("Settings.minBet");
                    $maxBet = Configure::read("Settings.maxBet");
                    $maxWining = Configure::read("Settings.maxWining");

                    if (!empty($credit)) {
                        $minBet = $minBet > 0 ?  round(($minBet * $credit["PaymentAppModel"]["amount"]) / 100) : $minBet;
                        $maxBet = $maxBet > 0 ?  round(($maxBet * $credit["PaymentAppModel"]["amount"]) / 100) : $maxBet;
                        $maxWining = $maxWining > 0 ?  round(($maxWining * $credit["PaymentAppModel"]["amount"]) / 100) : $maxWining;
                    }

                    Configure::write("Settings.minBet", $minBet);
                    Configure::write("Settings.maxBet", $maxBet);
                    Configure::write("Settings.maxWining", $maxWining);
                }
            }
        }catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }
}