<?php
/**
 * Dashboard Model
 *
 * Handles Dashboard Data Source Actions
 *
 * @package    Dashboard.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Dashboard extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Dashboard';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable bool
     */
    public $useTable = false;

    /**
     * Checks is current user dashboard is valid by theirs group
     *
     * @param $dashboard
     * @return bool
     */
    public function isDashboardGroupValid($dashboard) {
        return (strtolower(CakeSession::read('Auth.User.Group.name')) == $dashboard);
    }
}