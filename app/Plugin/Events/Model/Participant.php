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

class Participant extends EventsAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Participant';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'            =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),
        'import_id'     =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),
        'event_id'      =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),
        'country_id'    =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),
        'number'        => array(
            'type'      => 'int',
            'length'    => 1,
            'null'      => false
        ),
        'name'          => array(
            'type'      => 'text',
            'length'    => null,
            'null'      => false
        ),
        'type'          => array(
            'type'      => 'enum',
            'length'    => null,
            'null'      => false
        ),
        'gender'        => array(
            'type'      => 'enum',
            'length'    => null,
            'null'      => false
        ),
    );

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
	public $belongsTo = array(
		0   =>  'Country'
	);

    /**
     * Database table prefix for tables in model.
     *
     * @var string $tablePrefix
     */
    public $tablePrefix = 'events_';
}
