<?php
/**
 * 30 Min Cron Shell
 *
 * Handles 30 Min Cron Shell Tasks
 *
 * @package    App.Console
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

class Cron30MinShell extends Shell
{
    /**
     * Main Init Method
     *
     * @return void
     */
    public function execute()
    {
        /** Feed Update: Imports | Updates results 30 Min  */
        try {
            //$this->dispatchShell('Feeds.OddService updateResults30Min');
        }catch (Exception $e) {
            CakeLog::write('feed', $e);
        }

        /** Feed Update: Results | Update Results  */
        try {
//            $this->dispatchShell('Feeds.FeedApp importSports');
//            $this->dispatchShell('Feeds.FeedApp importLeagues');
//            $this->dispatchShell('Feeds.FeedApp importEvents');
//            $this->dispatchShell('Cron5Min execute');
        }catch (Exception $e) {
            CakeLog::write('feed', $e);
        }
    }
}