<?php
/**
 * Pin Model
 *
 * Handles Pin Data Source Actions
 */

App::uses('AppModel', 'Model');

class Pin extends AppModel
{

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Pin';

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
        ),
        'pin'       => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => false
        ),
        'amount'     => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => false
        ),
        'created_at'    => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        ),
        'used_at'    => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        ),
        'status'    => array(
            'type'      => 'string',
            'length'    => 10,
            'null'      => false
        ),
        'description'    => array(
            'type'      => 'string',
            'length'    => 250,
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

    const Created      =   'Created';
    const Used      =   'Used';
    const Pending      =   'Pending';
    /**
     * Withdraw online type value
     */
    const WITHDRAW_TYPE_ONLINE      =   1;

    /**
     * Withdraw offline type value
     */
    const WITHDRAW_TYPE_OFFLINE     =   2;

    /**
     * Withdraw type default value
     */
    const WITHDRAW_STATUS_DEFAULT   =   'pending';

    /**
     * Withdraw type pending value
     */
    const WITHDRAW_STATUS_PENDING   =   'pending';

    /**
     * Withdraw type completed value
     */
    const WITHDRAW_STATUS_COMPLETED =   'completed';

    /**
     * Withdraw type cancelled value
     */
    const WITHDRAW_STATUS_CANCELLED =   'cancelled';

    /**
     * Constructor. Binds the model's database table to the object.
     *
     * @param bool $id
     * @param null $table
     * @param null $ds
     */
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
    }

    /**
     * Returns user notice
     *
     * @param $username
     * @param $userId
     * @return mixed
     */
    public function getUsersNotice($username, $userId) {
        return __('Charged by Staff', $username, $userId);
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

    /***
     * Get deposits. user only or all
     *
     * @param       $userId
     * @param array $options
     *
     * @return array
     */
    public function getUserWithdraws($userId, $options = array()) {
        return $this->find('all', array(
            'contain'       =>  array(),
            'conditions'    =>  array_merge(
                array('Withdraw.user_id' => $userId), $options
            ),
            'order'         =>  'Withdraw.id DESC'
        ));
    }

    /**
     * Returns withdraw
     *
     * @param $id
     * @return array
     */
    public function getWithdraw($id)
    {    	
    	$options['conditions'] = array('Withdraw.id' => $id);
    	$options['fields'] = array('Withdraw.id','Withdraw.amount','Withdraw.date');
    	$this->contain();
    	$data = $this->find('first', $options);
    	return $data;
    }

    public function getPinByCode($pin) {
        return $this->find('first', array(
            'contain'       =>  null,
            'conditions'    =>  array(
                'Pin.pin'    =>  $pin
            ),
        ));
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
                'plugin'        =>  false,
                'controller'    =>  'Withdraws',
                'action'        =>  'admin_complete',
                'class'         =>  'btn btn-mini btn-primary'
            ),
            1   =>  array(
                'name'          =>  __('Cancel', true),
                'controller'    =>  'Withdraws',
                'action'        =>  'admin_cancel',
                'class'         =>  'btn btn-mini btn-danger'
            )
        );
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
                'name'      =>  __('Pending', true),
                'active'    =>  $params['action'] == 'admin_index',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'withdraws',
                    'action'        =>  'admin_index'
                )
            ),
            1   =>  array(
                'name'      =>  __('Completed', true),
                'active'    =>  $params['action'] == 'admin_completed',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'withdraws',
                    'action'        =>  'admin_completed'
                )
            ),
            2   =>  array(
                'name'      =>  __('Cancelled', true),
                'active'    =>  $params['action'] == 'admin_cancelled',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'withdraws',
                    'action'        =>  'admin_cancelled'
                )
            ),
        );
    }

    /**
     * Sets status
     *
     * @param $id
     * @param $status
     * @param null $description
     */
    public function setStatus($id, $status, $description = null)
    {
        $data = $this->getItem($id);

        $data['Withdraw']['status'] = $status;

        if (!is_null($description)) {
            $data['Withdraw']['description'] = $description;
        }

        $this->save($data);
    }

    public function setStatusByPin($pin, $status, $description = null)
    {
        $data = $this->getPinByCode($pin);

        $data['Pin']['status'] = $status;

        if (!is_null($description)) {
            $data['Pin']['description'] = $description;
        }

        $this->save($data);
    }


    /**
     * Gets pagination
     *
     * @param string $options
     * @return array
     */
    public function getPagination($options = 'Created',$user)
    {
        $condition=['Pin.status' => $options];
        if($user['group_id'] != Group::ADMINISTRATOR_GROUP) {
            $condition['Pin.user_id']=$user['id'];
        }
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'fields' => array(
                'Pin.id',
                'Pin.pin',
                'Pin.amount',
                'Pin.user_id',
                'User.username',
                'Pin.status',
                'Pin.description',
                'Pin.created_at',
                'Pin.used_at'
            ),
            'conditions' => $condition
        );

        return $pagination;
    }
    
     //RISKS function
    function getBigWithdraws($bigWithdraw) {
        $options['conditions'] = array(
            'Withdraw.amount >=' => $bigWithdraw
        );
        $options['order'] = 'Withdraw.amount DESC';
        $this->contain('User');
        $data = $this->find('all', $options);        
        return $data;
    }

    /**
     * Returns withdraw report
     *
     * @param $from
     * @param $to
     * @param null $userId
     * @param int $limit
     * @return array
     */
    public function getReport($from, $to, $userId = null, $limit = 10)
    {
        $options = array(
            'contain'       =>  array('User'),
            'conditions'    =>  array(
                'Withdraw.date BETWEEN ? AND ?' =>  array($from, $to),
                'Withdraw.status'               =>  self::WITHDRAW_STATUS_COMPLETED
            ),
            'limit'         =>  $limit
        );

        if ($userId != null) {
            $options['conditions']['Withdraw.user_id'] = $userId;
        }

        $data = $this->find('all', $options);

        $data['header'] = array(
            'Withdraw ID',
            'User ID',
            'User name',
            'First last name',
            'Bank name',
            'Bank code',
            'Account no.',
            'Request time',
            'Withdraw type',
            'Amount'
        );
        return $data;
    }

    /**
     * Returns scaffold view fields
     *
     * @param int $id - view entry id
     *
     * @return array
     */
    public function getView($id) {
    	$options['fields'] = array();
    	$options['recursive'] = -1;
    	$options['conditions'] = array($this->name . '.id' => $id);
    
    	foreach ($this->_schema as $key => $value) {
    		if (($key != 'id') && ($key != 'order')) {
    			$options['fields'][] = $this->name . '.' . $key;
    			
    		}
    	}    	
    	$data = $this->find('first', $options);
    	return $data;
    }

    public function addPinNew
    (
        $cardNo,
        $userId,
        $amount,
        $status = Pin::Created,
        $description
    )
    {
        $data = array(
            'user_id'           =>  $userId,
            'pin'           =>  $cardNo,
            'description'       =>  $description,
            'amount'            =>  $amount,
            'created_at'              =>  gmdate('Y-m-d H:i:s'),
            'status'            =>  $status
        );

        $this->create();

        if($this->save($data)) {
//            $this->getEventManager()->dispatch(new CakeEvent('Model.Withdraw.addWithdraw', $this, array(
//                'userId'    =>  $userId,
//                'amount'    =>  $amount
//            )));
            return true;
        }
        return false;
    }

    /**
     * Adds Withdraw
     *
     * @param $userId
     * @param $amount
     * @param $type
     * @param $description
     * @param string $status
     * @return bool
     */
    public function addWithdraw
    (
        $userId,
        $account,
        $amount,
        $type,
        $description,
        $status = Withdraw::WITHDRAW_STATUS_PENDING
    )
    {
        $data = array(
            'user_id'           =>  $userId,
            'withdraw_account'  =>  $account,
            'type'              =>  $type,
            'description'       =>  $description,
            'amount'            =>  $amount,
            'date'              =>  gmdate('Y-m-d H:i:s'),
            'status'            =>  $status
        );

        $this->create();

        if($this->save($data)) {
            $this->getEventManager()->dispatch(new CakeEvent('Model.Withdraw.addWithdraw', $this, array(
                'userId'    =>  $userId,
                'amount'    =>  $amount
            )));
            return true;
        }
        return false;
    }


    /**
     * Model scaffold search fields wrapper
     *
     * @return array
     */
    public function getSearch()
    {
        return array(

//            'Withdraw.user_id'       =>  array(
//                'label' =>  __('User ID'),
//                'type'  =>  'text'
//            ),
//
//            'Withdraw.type'          =>  array(
//                'type'  =>  'text'
//            ),

            'Withdraw.amount'        =>  array(
                'type'  =>  'hidden'
            ),

            'Withdraw.amount_from'   => $this->getFieldHtmlConfig('currency', array('label' => __('Amount from'))),

            'Withdraw.amount_to'     => $this->getFieldHtmlConfig('currency', array('label' => __('Amount to'))),

            'Withdraw.date'          => $this->getFieldHtmlConfig('date'),

//            'Withdraw.id'            => $this->getFieldHtmlConfig('number', array('label' => __('Identity number'))),
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
            'Withdraw.user_id'   =>  array(
                'Withdraw.user_id = ?'   =>  array(
                    0   =>  'Withdraw.user_id'
                )
            ),

            'Withdraw.type'   =>  array(
                'Withdraw.type LIKE ?'  =>  array(
                    0   =>  'Withdraw.type'
                )
            ),

            'Withdraw.amount'    =>  array(
                'Withdraw.amount BETWEEN ? AND ?' => array(
                    0   =>  'Withdraw.amount_from',
                    1   =>  'Withdraw.amount_to'
                )
            )
        );
    }

    /**
     * Deposit type wrapper
     *
     * @param $type
     * @return int
     * @throws Exception
     */
    public function getWithdrawType($type) {
        switch(strtolower($type)) {
            case 'withdraw_type_online':
                return self::WITHDRAW_TYPE_ONLINE;
                break;
            case 'withdraw_type_offline':
                return self::WITHDRAW_TYPE_OFFLINE;
                break;
            default:
                throw new Exception('Unknown withdraw type', 500);
                break;
        }
    }
}