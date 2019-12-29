<?php
/**
 * Result Model
 *
 * Handles Result Data Source Actions
 *
 * @package    Results.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Result extends AppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Result';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = false;

    /**
     * Returns admin scaffold tabs
     *
     * @param array $params - url params
     *
     * @return array
     */
    public function getTabs(array $params)
    {
        return array(
            0   =>  array(
                'name'      =>  __('Results', true),
                'active'    =>  $params['action'] == 'admin_index',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'results',
                    'action'        =>  'admin_index'
                )
            ),
            1   =>  array(
                'name'      =>  __('Finished events in tickets', true),
                'active'    =>  $params['action'] == 'admin_allSports',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'results',
                    'action'        =>  'admin_allSports'
                )
            ),
            2   =>  array(
                'name'      =>  __('Pending events in tickets', true),
                'active'    =>  $params['action'] == 'admin_pendingSports',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'results',
                    'action'        =>  'admin_pendingSports'
                )
            ),
            3   =>  array(
                'name'      =>  __('All finished events', true),
                'active'    =>  $params['action'] == 'admin_allAll',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'results',
                    'action'        =>  'admin_allAll'
                )
            ),
        );
    }
}