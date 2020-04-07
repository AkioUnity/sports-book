<?php
/**
 * 5 Min Cron Shell
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
//*/5 * * * * cd /var/www/html/app && php Console/cake.php -app app Cron5Min execute
class Cron5MinShell extends Shell
{
    /**
     * Main Init Method
     *
     * @return void
     */
    public function execute()
    {
        /** Feed Update: Imports | Updates Sports */
        try {
            $this->dispatchShell('Feeds.FeedApp importLeagues');
        }catch (Exception $e) {
            CakeLog::write('feed', $e);
        }
    }
}