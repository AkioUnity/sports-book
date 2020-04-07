<?php
/**
 * 1 Hour Cron Shell
 *
 * Handles 1 Hour Cron Shell Tasks
 *
 * @package    App.Console
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */
//0 * * * * cd /var/www/html/app && php Console/cake.php -app app Cron1Hour execute
class Cron1HourShell extends Shell
{
    /**
     * Main Init Method
     *
     * @return void
     */
    public function execute()
    {
        try {
        }catch (Exception $e) {
            CakeLog::write('queue', $e);
        }
    }
}