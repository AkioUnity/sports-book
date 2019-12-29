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
App::uses('ResultsListener', 'Events.Event');

class Result extends EventsAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Result';

    /**
     * Results hash map
     *
     * @var array
     */
    private $_hashMap = array();

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
        'import_id'         =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),
        'participant_id'    =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),
        'event_id'          =>  array(
            'type'      => 'int',
            'length'    => 22,
            'null'      => false
        ),
        'result_code'       => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => false
        ),
        'value'          => array(
            'type'      => 'int',
            'length'    => 10,
            'null'      => false
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array(
        0   =>  'Event.Participant',
        1   =>  'App.Event'
    );

    /**
     * Database table prefix for tables in model.
     *
     * @var string $tablePrefix
     */
    public $tablePrefix = 'events_';

    /**
     * Constructor. Binds the model's database table to the object.
     *
     * @param bool $id
     * @param null $table
     * @param null $ds
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new ResultsListener());
    }

    /**
     * Called after each successful save operation.
     * Saves insert ids
     *
     * @param bool  $created - True if this save created a new record
     * @param array $options - Options passed from Model::save().
     *
     * @return bool|void
     */
    public function afterSave($created, $options = array())
    {
        $hash = array(
            0   =>  $this->data[$this->name]["event_id"],
            1   =>  $this->data[$this->name]["result_code"]
        );

        $hash = md5(implode('', $hash));

        if (!isset($this->_hashMap[$hash])) {
            $this->_hashMap[$hash] = array();
        }

        $this->_hashMap[$hash][] = $this->data[$this->name];

        foreach ($this->_hashMap AS $hash => $map) {
            if (count($map) == 2) {
                $this->getEventManager()->dispatch(new CakeEvent('Model.Results.afterSave', $this, array(
                    "Results"   =>  $map
                )));
                unset($this->_hashMap[$hash]);
            }
        }
    }

    public function getResultsHashMap()
    {
        return $this->_hashMap;
    }
}