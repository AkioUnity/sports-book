<?php
/**
 * Deposit Model
 *
 * Handles Deposit Data Source Actions
 *
 * @package    Deposits.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');
App::uses('NotificationListener', 'Event');

class Notification extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Notification';

    /**
     * DB table
     *
     * @var string
     */
    public $table = "users_notifications";

    /**
     *  DB table
     *
     * @var string
     */
    public $useTable = "users_notifications";

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'user_id'     => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'text'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
       'date'    => array(
            'type'      => 'datetime',
            'length'    => null,
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
    public $belongsTo = array('User');

    /**
     * Notification constructor.
     * @param bool $id
     * @param null $table
     * @param null $ds
     */
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new NotificationListener());
    }

    /**
     * Add notification
     *
     * @param $userId
     * @param $text
     *
     * @return mixed
     */
    public function add($userId, $text)
    {
        $this->create();

        $data = $this->save(array(
            $this->name =>  array(
                "user_id"   =>  $userId,
                "text"      =>  $text,
                "date"      =>  gmdate('Y-m-d H:i:s')
            )
        ));

        $this->getEventManager()->dispatch(new CakeEvent('Model.Notification.add', $this, $data[$this->name]));

        return $data;
    }
}