<?php

App::uses('PokerAppModel', 'Poker.Model');

class PokerTable extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'PokerTable';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'poker_poker';

    public $primaryKey = 'gameID';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'gameID'        => array(
            'type'      => 'int',
            'length'    => 15,
            'null'      => false
        ),
        'tablename'       => array(
            'type'      => 'string',
            'length'    => 64,
            'null'      => true
        ),
        'tabletype'       => array(
            'type'      => 'string',
            'length'    => 1,
            'null'      => true
        ),
        'tablelow'       => array(
            'type'      => 'int',
            'length'    => 7,
            'null'      => true
        ),
        'tablelimit'       => array(
            'type'      => 'string',
            'length'    => 15,
            'null'      => true
        ),
        'tablestyle'       => array(
            'type'      => 'string',
            'length'    => 20,
            'null'      => true
        ),
        'pot'        => array(
            'type'      => 'int',
            'length'    => 10,
            'null'      => false
        ),
        'bet'        => array(
            'type'      => 'int',
            'length'    => 10,
            'null'      => false
        ),
        'lastmove'        => array(
            'type'      => 'int',
            'length'    => 35,
            'null'      => false
        ),
    );

    public function insertTable($name, $type, $min, $max)
    {
        $this->create();

        $PokerTable = array(
            'PokerTable'   =>  array(
                'tablename' =>  $name,
                'tabletype'      =>  $type,
                'tablelow'    =>  $min,
                'tablelimit'       =>  $max,
                'tablestyle'      =>  'default',
                'pot'      =>  0,
                'bet'      =>  0,
                'lastmove'      =>  0,
            )
        );

        $this->save($PokerTable);

        return true;
    }

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
                'name'      =>  __('Poker tables', true),
                'active'    =>  $params['action'] == 'admin_tables',
                'url'       =>  array(
                    'plugin'        =>  'poker',
                    'controller'    =>  'PokerTables',
                    'action'        =>  'admin_tables'
                )
            ),
            1   =>  array(
                'name'      =>  __('Create poker table', true),
                'active'    =>  $params['action'] == 'admin_create',
                'url'       =>  array(
                    'plugin'        =>  'poker',
                    'controller'    =>  'PokerTables',
                    'action'        =>  'admin_create'
                )
            )
        );
    }
}