<?php

App::uses('PokerAppModel', 'Poker.Model');

class PokerLog extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'PokerLog';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'poker_logs';
	
	public function beforeSave($options = array()) {
		
		return true;
	}

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
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array('User');

    /**
     * Writes log
     *
     * @param $userId
     * @param $message
     * @return mixed
     */
    public function write($userId, $message)
    {
        $data['PokerLog']['user_id'] = $userId;
        $data['PokerLog']['message'] = $message;
        return $this->saveAll($data);
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
