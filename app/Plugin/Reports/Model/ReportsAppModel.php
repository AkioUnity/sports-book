<?php

App::uses('AppModel', 'Model');

class ReportsAppModel extends AppModel {
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'ReportsApp';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable bool
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
            'admin_users'               =>  array(
                'name'      =>  __('Users', true),
                'active'    =>  $params['action'] == 'admin_reports',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'users',
                    'action'        =>  'admin_reports'
                )
            ),
            'admin_tickets'             =>  array(
                'name'      =>  __('Tickets', true),
                'active'    =>  $params['action'] == 'admin_reports',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'tickets',
                    'action'        =>  'admin_reports'
                )
            ),
            'admin_deposits'            =>  array(
                'name'      =>  __('Deposits', true),
                'active'    =>  $params['action'] == 'admin_reports',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'deposits',
                    'action'        =>  'admin_reports'
                )
            ),
            'admin_withdraws'           =>  array(
                'name'      =>  __('Withdraws', true),
                'active'    =>  $params['action'] == 'admin_reports',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'withdraws',
                    'action'        =>  'admin_reports'
                )
            ),
        );
    }
}