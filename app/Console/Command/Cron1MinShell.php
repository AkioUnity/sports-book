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
            $this->dispatchShell('Queue.queue process');
        }catch (Exception $e) {
            CakeLog::write('queue', $e);
        }
    }
}