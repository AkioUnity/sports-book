<?php
/**
 * Handles Feeds
 *
 * Handles Feeds User Interface Actions
 *
 * @package    Feeds
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

class FeedsController extends FeedsAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Feeds';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Feeds.Feed');

    const NORDICBET = 'nordicbet';

    const LINE = 'line';


    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter() {

        set_time_limit(0);

        parent::beforeFilter();
    }

    function admin_updateAll($download = true) {
        if ($download) {
            $this->downloadAll();
        }

        $this->parseAll();

        $this->Admin->setMessage(__('All feeds updated', true), 'success');

        CakeLog::write('feed', 'updateAll()');
        $this->redirect(array('action' => 'index'));
    }

    public function downloadAll() {
        $feeds = $this->Feed->getActiveFeeds();

        foreach ($feeds as $key => $feed) {
            $this->download($feed['Feed']['id']);
        }

        $this->Admin->setMessage(__('All feeds downloaded', true), 'success');
    }

    function parseAll() {
        $feeds = $this->Feed->getActiveFeeds();
        foreach ($feeds as $key => $feed) {
            //parse only dowanloaded files
            $this->update($feed['Feed']['id'], false);
        }
        $this->Admin->setMessage(__('All feeds parsed', true), 'success');
    }

    function admin_manualUpdate() {
        $feeds = $this->Feed->getActiveFeeds();
        $log['total'] = 0;
        $log[] = 'start update...';
        $this->Session->write('log', $log);
        $this->Session->write('feeds', $feeds);
        $this->redirect(array('action' => 'updating'));
    }

    function admin_updating() {
        $feeds = $this->Session->read('feeds');
        $log = $this->Session->read('log');
        if (empty($feeds)) {
            $log[] = 'done';
            $this->Session->write('log', $log);
            $this->Session->write('feeds', null);
            $this->Admin->setMessage(__('All feeds updated', true), 'success');

            $this->flash('continue...', array('action' => 'index'), 1);
            //$this->redirect(array('action' => 'index'));
        }
        foreach ($feeds as $key => $feed) {
            $startTime = microtime(true);

            $this->update($feed['Feed']['id']);
            unset($feeds[$key]);
            $this->Session->write('feeds', $feeds);

            $endTime = microtime(true);
            $time = $endTime - $startTime;

            $log[] = 'updated ' . $feed['Feed']['name'] . ' in ' . $time;
            $log['total'] += $time;
            $this->Session->write('log', $log);

            $this->flash('continue...', array('action' => 'updating'), 1);
            //$this->redirect(array('action' => 'updating'));
            break;
        }
    }

    function admin_update($id = NULL, $download = true, $skip = 0) {
        $this->update($id, $download, $skip);
        $this->Admin->setMessage(__('Feed updated', true), 'success');
        $this->redirect(array('action' => 'index'));
    }

    public function download($id = NULL) {

        $feed = $this->Feed->getFeed($id);

        if (empty($feed)) {
            $this->Admin->setMessage(__('Cannot find feed', true), 'error');
            return false;
        }

        $url = $this->__getFeedUrl($feed['Feed']['url']);

        $directory = APP . 'tmp' . DS . 'xml' . DS;

        if(!file_exists($directory))
            mkdir( $directory, 0777, true );

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);

        curl_close($ch);
        file_put_contents($directory . Inflector::slug($feed['Feed']['name']) . '.xml', $data);

        return $data;
    }

    function update($id = NULL, $download = 1, $skip = 0) {

        $feed = $this->Feed->getFeed($id);

        if (empty($feed)) {
            $this->Admin->setMessage(__('Cannot find feed', true), 'error');
            $this->redirect(array('controller' => 'feeds', 'action' => 'index'));
        }

        //set timezone
        $timezone = $feed['Feed']['timezone'];
        date_default_timezone_set($timezone);

        $url = APP . 'tmp' . DS . 'xml' . DS . Inflector::slug($feed['Feed']['name']) . '.xml';

        if (file_exists($url)) {
            $xml = simplexml_load_file($url);
        } else {
            if ($download) {
                $xml = $this->download($id);
                $this->redirect(array('controller' => 'feeds', 'action' => 'update', $id));
                return;
            } else {
                return;
            }
        }

        if (preg_match('/nordicbet/', $feed['Feed']['url'])) {
            $this->__update_nordicbet($xml);
        }

        $this->Feed->updated($id, gmdate('Y-m-d H:i:s'));
    }


    private function __update_nordicbet($xml) {
        $startTime = microtime(true);
        //skip first games
        if (isset($this->request->params['pass'][2])) {
            $skip = $this->request->params['pass'][2];
        }
        $gameNr = 0;


    }

    private function __getFeedUrl($url) {
        if (preg_match('/xml\.nordicbet/', $url)) {
            return $url;
        }
        return $url;
    }
}