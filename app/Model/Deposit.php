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
App::uses('DepositListener', 'Event');

class Deposit extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Deposit';

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
        'type'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'amount'     => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => false
        ),
        'date'    => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        ),
        'deposit_id'    => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => false
        ),
        'status'    => array(
            'type'      => 'string',
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
    public $belongsTo = array('User');

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = array('Referral');

    /**
     * Deposit type fund value
     */
    const DEPOSIT_TYPE_ONLINE         =   1;

    /**
     * Deposit type charge value
     */
    const DEPOSIT_TYPE_OFFLINE       =   2;

    /**
     * Deposit status default value
     */
    const DEPOSIT_STATUS_DEFAULT     =   'pending';

    /**
     * Deposit status pending value
     */
    const DEPOSIT_STATUS_PENDING     =   'pending';

    /**
     * Deposit status completed value
     */
    const DEPOSIT_STATUS_COMPLETED   =   'completed';

    /**
     * Deposit status cancelled value
     */
    const DEPOSIT_STATUS_CANCELLED   =   'cancelled';

    /**
     * Constructor. Binds the model's database table to the object.
     *
     * @param bool $id
     * @param null $table
     * @param null $ds
     */
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new DepositListener());
    }

    /**
     * Returns user notice
     *
     * @param $username
     * @param $userId
     * @return mixed
     */
    public function getUsersNotice($username, $userId) {
        return __('Credited by staff', $username, $userId);
    }

    /**
     * Returns staff notice
     *
     * @param $username
     * @param $userId
     * @param $staffUsername
     * @return mixed
     */
    public function getStaffNotice($username, $userId, $staffUsername) {
        return __('User %s (ID: %d) account was credited by %s', $username, $userId, $staffUsername);
    }

    /**
     * Deposit type wrapper
     *
     * @param $type
     * @return int
     * @throws Exception
     */
    public function getDepositType($type) {
        switch(strtolower($type)) {
            case 'deposit_type_online':
                return self::DEPOSIT_TYPE_ONLINE;
            break;
            case 'deposit_type_offline':
                return self::DEPOSIT_TYPE_OFFLINE;
            break;
            default:
                throw new Exception('Unknown deposit type', 500);
            break;
        }
    }

    /**
     * Deposit status wrapper
     *
     * @param $status
     * @return string
     */
    public function getDepositStatus($status) {
        switch(strtolower($status)) {
            case 'default':
            case 'deposit_status_default':
                return self::DEPOSIT_STATUS_DEFAULT;
                break;
            case 'pending':
            case 'deposit_status_pending':
                return self::DEPOSIT_STATUS_PENDING;
                break;
            case 'completed':
            case 'deposit_status_completed':
                return self::DEPOSIT_STATUS_COMPLETED;
                break;
            case 'cancelled':
            case 'deposit_status_cancelled':
                return self::DEPOSIT_STATUS_CANCELLED;
                break;
            default:
                return self::DEPOSIT_STATUS_DEFAULT;
                break;
        }
    }

    /**
     * Returns deposits data
     *
     * @param string $options
     * @return array
     */
    public function getPagination($options = self::DEPOSIT_STATUS_PENDING)
    {
        return array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'fields' => array(
                'Deposit.id',
                'Deposit.user_id',
                'User.username',
                'Deposit.date',
                'Deposit.type',
                'Deposit.amount',
                'Deposit.phone_number',
                'Deposit.description',
                'Deposit.status'
            ),
            'conditions' => array(
                'Deposit.status' => $options
            )
        );
    }

    /**
     * Adds Deposit
     *
     * @param $userId
     * @param $amount
     * @param $phoneNumber
     * @param int $type
     * @param string $description
     * @param string $status
     * @return bool
     */
    public function addDeposit
    (
        $userId,
        $amount,
        $phoneNumber = null,
        $type = self::DEPOSIT_TYPE_ONLINE,
        $description = '',
        $status = self::DEPOSIT_STATUS_PENDING
    )
    {
        $this->create();

        $result = $this->save(array(
            'Deposit'   =>  array(
                'user_id'       =>  $userId,
                'amount'        =>  $amount,
                'phone_number'  =>  $phoneNumber,
                'online'        =>  $type,
                'type'          =>  $type,
                'description'   =>  $description,
                'date'          =>  gmdate('Y-m-d H:i:s'),
                'deposit_id'    =>  uniqid($userId . '-'),
                'status'        =>  Deposit::DEPOSIT_STATUS_PENDING
            )
        ));

        $this->setStatus($this->id, $status);

        return $result;
    }

    /**
     * Update deposit
     *
     * @param $id
     * @param null $amount
     * @param null $type
     * @return mixed
     */
    public function updateDeposit($id, $amount = null, $type = null)
    {
        $deposit = $this->getItem($id);
        if ($amount !== null) {
            $deposit['Deposit']['amount'] = $amount;
        }
        if ($type !== null) {
            $deposit['Deposit']['type'] = $type;
        }

        return $this->save($deposit);
    }

    /**
     * Returns deposit by id
     *
     * @param $depositId
     * @return array
     */
    public function getDepositById($depositId) {
        return $this->find('first', array(
            'contain'       =>  null,
            'conditions'    =>  array(
                'Deposit.deposit_id'    =>  $depositId
            ),
        ));
    }

    /**
     * Returns deposits by type
     *
     * @param string $status
     * @return array
     */
    public function getDepositsByStatus($status = self::DEPOSIT_STATUS_PENDING) {
        return $this->find('all', array(
            'conditions'    =>  array(
                'Deposit.status' => $status
            ),
            'recursive' =>  -1
        ));
    }

    //get deposits. user only or all
    public function get($id) {
        return $this->find('all', array(
            'contain'       =>   '',
            'conditions'    =>  array(
                'Deposit.user_id' => $id
            )
        ));
    }

    /**
     * Returns deposits by amount
     *
     * @param $amount
     * @return array
     */
    public function getDepositsByAmount($amount) {
        return $this->find('all', array(
            'contain'       =>  'User',
            'conditions'    =>  array(
                'Deposit.amount >=' => $amount
            )
        ));
    }

    /**
     * getReport
     *
     * @param $from
     * @param $to
     * @param null $userId
     * @param null $limit
     * @return array
     */
    public function getReport($from, $to, $userId = null, $limit = null)
    {
        $options['conditions'] = array(
            'Deposit.date BETWEEN ? AND ?'  => array($from, $to),
            'Deposit.status'                => self::DEPOSIT_STATUS_COMPLETED
        );

        if ($userId != null)
            $options['conditions']['Deposit.user_id'] = $userId;

        if ($limit != null)
            $options['limit'] = $limit;

        $options['order'] = array('Deposit.date DESC');

        $this->contain('User');

        $data = $this->find('all', $options);

        $data['header'] = array(
            'Deposit ID',
            'User ID',
            'User name',
            'Deposit time',
            'Deposit type',
            'Amount'
        );

        return $data;
    }

    /**
     * Updates status for existing deposit
     *
     * @param $id
     * @param $status
     *
     * @return bool
     */
    public function setStatus($id, $status, $paymentRequest = null)
    {
        $Deposit = $this->getItem($id);

        if(empty($Deposit)) { return false; }

        $this->getEventManager()->dispatch(new CakeEvent('Model.Deposit.setStatus', $this, array(
            'status'            =>  $status,
            'userId'            =>  $Deposit['Deposit']['user_id'],
            'amount'            =>  $Deposit['Deposit']['amount'],
            'paymentCallback'   =>  $paymentRequest == true
        )));

        $Deposit['Deposit']['status'] = $status;

        $this->save($Deposit);

        return true;
    }

    /**
     * Returns admin scaffold tabs
     *
     * @param array $params - url params
     *
     * @return array
     */
    public function getTabs(array $params) {
        return array(
            0   =>  array(
                'name'      =>  __('Pending', true),
                'active'    =>  $params['action'] == 'admin_index',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'deposits',
                    'action'        =>  'admin_index'
                )
            ),
            1   =>  array(
                'name'      =>  __('Completed', true),
                'active'    =>  $params['action'] == 'admin_completed',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'deposits',
                    'action'        =>  'admin_completed'
                )
            ),
            2   =>  array(
                'name'      =>  __('Cancelled', true),
                'active'    =>  $params['action'] == 'admin_cancelled',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'deposits',
                    'action'        =>  'admin_cancelled'
                )
            )
        );
    }

    /**
     * Returns scaffold actions list
     *
     * @param CakeRequest $cakeRequest - cakeRequest object
     *
     * @return array
     */
    public function getActions(CakeRequest $cakeRequest)
    {
        return array(
            0   =>  array(
                'name'          =>  __('Complete', true),
                'controller'    =>  null,
                'action'        =>  'admin_complete',
                'class'         =>  'btn btn-mini btn-primary'
            ),
            1   =>  array(
                'name'          =>  __('Cancel', true),
                'controller'    =>  null,
                'action'        =>  'admin_cancel',
                'class'         =>  'btn btn-mini btn-danger'
            )
        );
    }

    /**
     * Returns search fields
     *
     * @return array
     */
    public function getSearch()
    {
        return array(

//            'Deposit.user_id'       =>  array(
//                'type'  =>  'text',
//                'label' =>  __('User ID'),
//            ),
//
//            'Deposit.type'          =>  array(
//                'type'  =>  'text'
//            ),

            'Deposit.amount'        =>  array(
                'type'  =>  'hidden'
            ),

            'Deposit.amount_from'   => $this->getFieldHtmlConfig('currency', array('label' => __('Amount from'))),

            'Deposit.amount_to'     => $this->getFieldHtmlConfig('currency', array('label' => __('Amount to'))),

            'Deposit.date'          => $this->getFieldHtmlConfig('date'),

//            'Deposit.deposit_id'    => array('type' => 'text', 'label' => __('Transaction ID'))
        );
    }

    /**
     * Returns search fields
     *
     * @param array $data - fields data array
     *
     * @return array
     */
    public function getSearchFields(array $data)
    {
        return array(
            'Deposit.user_id'   =>  array(
                'Deposit.user_id = ?'   =>  array(
                    0   =>  'Deposit.user_id'
                )
            ),

            'Deposit.type'   =>  array(
                'Deposit.type LIKE ?'  =>  array(
                    0   =>  'Deposit.type'
                )
            ),

            'Deposit.amount'    =>  array(
                'Deposit.amount BETWEEN ? AND ?' => array(
                    0   =>  'Deposit.amount_from',
                    1   =>  'Deposit.amount_to'
                )
            )
        );
    }
}