<?php
/**
 * 1 Min Cron Shell
 *
 * Handles 5 Min Cron Shell Tasks
 *
 * @package    App.Console
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */
//php Console/cake.php -app app Cron1Min execute
//* * * * * cd /var/www/html/app && php Console/cake.php -app app Cron1Min execute

class Cron1MinShell extends Shell
{
    /**
     * Main Init Method
     *
     * @return void
     */
    public function execute()
    {
        /** Queue processor | Process queues */
        try {
//            $this->dispatchShell('Queue.queue process');
//            $this->dispatchShell('Feeds.FeedApp importLeagues');
            $this->dispatchShell('Feeds.FeedApp importEvents');  //309
            $this->dispatchShell('Feeds.Bet365 checkResult');
            $start = microtime(true);
            set_time_limit(60);
            $step=5;
//            $this->dispatchShell('Feeds.FeedApp live');  //309
            for ($i = $step; $i < 60; $i=$i+$step) {
                time_sleep_until($start + $i);
                $this->dispatchShell('Feeds.FeedApp live');  //309
            }
        }catch (Exception $e) {
            CakeLog::write('queue', $e);
        }
    }
}