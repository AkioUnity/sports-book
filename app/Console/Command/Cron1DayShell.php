<?php
/**
 * 1 Day Cron Shell
 *
 * Handles 1 Day Cron Shell Tasks
 *
 * @package    App.Console
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */
//0 0 * * * cd /var/www/html/app && php Console/cake.php -app app Cron1Day execute
class Cron1DayShell extends Shell
{
    /**
     * Main Init Method
     *
     * @return void
     */
    public function execute()
    {
        /** Feed Update: Imports | Updates Leagues */
        try {
//            $this->dispatchShell('Feeds.FeedApp importLeagues');
        }catch (Exception $e) {
            CakeLog::write('feed', $e);
        }

        /** User weeklyBalance processor | Process queues */
        try {
//            $this->dispatchShell('User weeklyBalance');
        }catch (Exception $e) {
            CakeLog::write('queue', $e);
        }
    }
}