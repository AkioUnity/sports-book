<?php
/**
 * Participant Model
 *
 * Handles Participant Data Actions
 *
 * @package    Participant.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */
App::uses('EventsAppModel', 'Events.Model');

class Scope extends EventsAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Scope';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'                =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),

        'parent_id'    =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),

        'sport_id'    =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),

        'lft'    =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),

        'rght'    =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),

        'map_value'       => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => false
        ),

        'system_value'      => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => false
        )
    );

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
	public $belongsTo = array(
		0   =>  'App.Event'
	);

    /**
     * Database table prefix for tables in model.
     *
     * @var string $tablePrefix
     */
    public $tablePrefix = 'events_';
}