<?php
/**
 * Runcpa Model
 *
 * Handles Runcpa Data Source Actions
 *
 * @package    Withdraws.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class RuncpaModel extends AppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Runcpa';

    /**
     * DB table
     *
     * @var string
     */
    public $table = "users_runcpa";

    /**
     *  DB table
     *
     * @var string
     */
    public $useTable = "users_runcpa";

    /**
     * Model schema
     *
     * @var array
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
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array('User');

    /**
     * Store cpa
     *
     * @param $userId
     * @param $trackId
     */
    public function store($userId, $trackId)
    {
        $cpa = $this->get($userId);

        if (empty($cpa)) {
            $this->save(array("user_id" => $userId, "track_id" => $trackId, "updated" => gmdate('Y-m-d H:i:s')));
        }
    }

    /**
     * Update cpa timestamp
     *
     * @param $userId
     */
    public function updated($userId)
    {
        $cpa = $this->get($userId);

        if (!empty($cpa)) {
            $this->id = $cpa["RuncpaModel"]["id"];
            $this->saveField('updated', gmdate('Y-m-d H:i:s'));
        }
    }

    /**
     * Get cpa
     *
     * @param $userId
     * @return array
     */
    public function get($userId)
    {
        return $this->find('first', array(
            "conditions"    =>  array(
                "user_id"   =>  $userId
            )
        ));
    }
}