<?php
/**
 * Log Model
 *
 * Handles Log Data Source Actions
 *
 * @package    Logs.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Log extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Log';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'logs';

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
        'user_id'   => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'message'   => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'created'    => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        )
    );

    /**
     * Writes log
     *
     * @param $userId
     * @param $message
     * @return mixed
     */
    public function write($userId, $message)
    {
        $data['Log']['user_id'] = $userId;
        $data['Log']['message'] = $message;
        return $this->save($data);
    }

    /**
     * ???
     *
     * @return array
     */
    public function getItemActions() {
        return array();
    }
}
