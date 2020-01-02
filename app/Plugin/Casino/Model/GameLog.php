<?php

App::uses('CasinoAppModel', 'Casino.Model');

class GameLog extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'GameLog';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'game_logs';
	
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
		'game'   => array(
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
	 * @param $game
     * @return mixed
     */
    public function write($userId, $message, $game)
    {
        $data['GameLog']['user_id'] = $userId;
        $data['GameLog']['message'] = $message;
		$data['GameLog']['game'] = $game;
        return $this->saveAll($data);
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
                'name'      =>  __('High Low', true),
                'active'    =>  $params['action'] == 'admin_highlow',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_highlow'
                )
            ),
            1   =>  array(
                'name'      =>  __('BlackJack', true),
                'active'    =>  $params['action'] == 'admin_blackjack',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_blackjack'
                )
            ),
            2   =>  array(
                'name'      =>  __('Roulettes', true),
                'active'    =>  $params['action'] == 'admin_roulette',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_roulette'
                )
            ),
			3   =>  array(
                'name'      =>  __('Baccarat', true),
                'active'    =>  $params['action'] == 'admin_baccarat',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_baccarat'
                )
            ),
			4   =>  array(
                'name'      =>  __('Caribbean Stud', true),
                'active'    =>  $params['action'] == 'admin_stud',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_stud'
                )
            ),
			5   =>  array(
                'name'      =>  __('Scratch fruit', true),
                'active'    =>  $params['action'] == 'admin_scratch',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_scratch'
                )
            ),
			6   =>  array(
                'name'      =>  __('Slot Christmas', true),
                'active'    =>  $params['action'] == 'admin_slot_christmas',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_slot_christmas'
                )
            ),
			7   =>  array(
                'name'      =>  __('Slot chicken', true),
                'active'    =>  $params['action'] == 'admin_slot_chicken',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_slot_chicken'
                )
            ),
			8   =>  array(
                'name'      =>  __('Slot ramses', true),
                'active'    =>  $params['action'] == 'admin_slot_ramses',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_slot_ramses'
                )
            ),
			9   =>  array(
                'name'      =>  __('Slot space', true),
                'active'    =>  $params['action'] == 'admin_slot_space',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_slot_space'
                )
            ),
			10   =>  array(
                'name'      =>  __('Slot fruits', true),
                'active'    =>  $params['action'] == 'admin_slot_fruits',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_slot_fruits'
                )
            ),
            11   =>  array(
                'name'      =>  __('Keno', true),
                'active'    =>  $params['action'] == 'admin_keno',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_keno'
                )
            ),
            12   =>  array(
                'name'      =>  __('Bingo', true),
                'active'    =>  $params['action'] == 'admin_bingo',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_bingo'
                )
            ),
            13   =>  array(
                'name'      =>  __('Greyhound Racing', true),
                'active'    =>  $params['action'] == 'admin_greyhound',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_greyhound'
                )
            ),
            14   =>  array(
                'name'      =>  __('Horse Racing', true),
                'active'    =>  $params['action'] == 'admin_horse',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_horse'
                )
            ),
            15   =>  array(
                'name'      =>  __('Soccer slots', true),
                'active'    =>  $params['action'] == 'admin_slot_soccer',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_slot_soccer'
                )
            ),
            16   =>  array(
                'name'      =>  __('Soccer slots 3D', true),
                'active'    =>  $params['action'] == 'admin_slot_soccer3d',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_slot_soccer3d'
                )
            ),
            17   =>  array(
                'name'      =>  __('Arabian slots', true),
                'active'    =>  $params['action'] == 'admin_slot_arabian',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameLogs',
                    'action'        =>  'admin_slot_arabian'
                )
            )
        );
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
