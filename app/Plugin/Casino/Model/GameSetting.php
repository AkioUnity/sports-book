<?php

App::uses('CasinoAppModel', 'Casino.Model');

class GameSetting extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'game_settings';

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
        'value'     => array(
            'type'      => 'string',
            'length'    => 3200,
            'null'      => false
        ),
        'key'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        )
    );

    /**
     * Save settings
     *
     * @param $settings
     * @param $settingsType
     * @return bool|mixed
     */
    public function saveSettings($settings, $settingsType)
    {
        switch($settingsType) {
            case 'highLowSettings':
                $db_settings = $this->getHighLowSettings();
                break;

            case 'blackJackSettings':
                $db_settings = $this->getBlackJackSettings();
                break;
				
			case 'rouletteSettings':
                $db_settings = $this->getRouletteSettings();
                break;
				
			case 'baccaratSettings':
                $db_settings = $this->getBaccaratSettings();
                break;
				
			case 'studSettings':
                $db_settings = $this->getStudSettings();
                break;
				
			case 'scratchSettings':
                $db_settings = $this->getScratchSettings();
                break;
				
			case 'slotChristmasSettings':
                $db_settings = $this->getSlotChristmasSettings();
                break;
				
			case 'slotChickenSettings':
                $db_settings = $this->getSlotChickenSettings();
                break;
				
			case 'slotRamsesSettings':
                $db_settings = $this->getSlotRamsesSettings();
                break;
				
			case 'slotSpaceSettings':
                $db_settings = $this->getSlotSpaceSettings();
                break;
				
			case 'slotFruitsSettings':
                $db_settings = $this->getSlotFruitsSettings();
                break;

            case 'kenoSettings':
                $db_settings = $this->getKenoSettings();
                break;

            case 'bingoSettings':
                $db_settings = $this->getBingoSettings();
                break;

            case 'greyhoundSettings':
                $db_settings = $this->getGreyhoundSettings();
                break;

            case 'horseSettings':
                $db_settings = $this->getHorseSettings();
                break;

            case 'slotSoccerSettings':
                $db_settings = $this->getSlotSoccerSettings();
                break;

            case 'slotSoccer3dSettings':
                $db_settings = $this->getSlotSoccer3dSettings();
                break;

            case 'slotArabianSettings':
                $db_settings = $this->getSlotArabianSettings();
                break;

            default:
                return false;
                break;
        }

        $settings = array_map(function($setting) USE ($settings) {
            if(isset($settings['GameSetting'][$setting['id']])) {
                return array(
                    'id'    =>  $setting['id'],
                    'value' =>  $settings['GameSetting'][$setting['id']]
                );
            }
            return array();
        }, $db_settings);
		
		

        return $this->saveAll(array_filter(array_values($settings)));
    }

    /**
     * Updates setting field
     *
     * @param $field
     * @param $value
     */
    function updateField($field, $value) {
        $options['conditions'] = array(
            'Setting.key' => $field
        );
        $data = $this->find('first', $options);
        $data['Setting']['value'] = $value;
        $this->save($data);
    }

    /**
     * Returns High Low game settings
     *
     * @return array
     */
    public function getHighLowSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'high_low_win_occurence',
				'high_low_max_possible_winings',
				'high_low_fiches_value_1',
				'high_low_fiches_value_2',
				'high_low_fiches_value_3',
				'high_low_fiches_value_4',
				'high_low_fiches_value_5',
				'high_low_card_turn_speed',
				'high_low_show_text_speed',
				'high_low_show_credits',
				'high_low_fullscreen',
				'high_low_check_orientation',
				'high_low_auto_lose_threshold'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }

    /**
     * Returns BlackJack game settings
     *
     * @return array
     */
    public function getBlackJackSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'black_jack_win_occurence',
				'black_jack_min_bet',
				'black_jack_max_bet',
				'black_jack_bet_time',
				'black_jack_payout',
				'black_jack_game_cash',
				'black_jack_show_credits',
				'black_jack_fullscreen',
				'black_jack_check_orientation',
				'black_jack_auto_lose_threshold'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }
	
	/**
     * Returns Roulette game settings
     *
     * @return array
     */
    public function getRouletteSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'roulette_win_occurence',
				'roulette_min_bet',
				'roulette_max_bet',
				'roulette_time_bet',
				'roulette_time_winner',
				'roulette_casino_cash',
				'roulette_fullscreen',
				'roulette_check_orientation',
				'roulette_auto_lose_threshold'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }
	
	/**
     * Returns Baccarat game settings
     *
     * @return array
     */
    public function getBaccaratSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'baccarat_win_occurence',
				'baccarat_bet_occurence_tie',
				'baccarat_bet_occurence_banker',
				'baccarat_bet_occurence_player',
				'baccarat_min_bet',
				'baccarat_max_bet',
				'baccarat_multiplier_tie',
				'baccarat_multiplier_banker',
				'baccarat_multiplier_player',
				'baccarat_auto_lose_threshold',
				'baccarat_time_show_hand',
				'baccarat_fullscreen',
				'baccarat_check_orientation'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }	
	
	/**
     * Returns Caribbean Stud game settings
     *
     * @return array
     */
    public function getStudSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'stud_win_occurence',
				'stud_min_bet',
				'stud_max_bet',
				'stud_auto_lose_threshold',
				'stud_payout_royal_flush',
				'stud_payout_straight_flush',
				'stud_payout_four_of_a_kind',
				'stud_payout_full_house',
				'stud_payout_flush',
				'stud_payout_straight',
				'stud_payout_three_of_a_kind',
				'stud_payout_two_pair',
				'stud_payout_one_pair_or_less',
				'stud_time_show_hand',
				'stud_show_credits',
				'stud_fullscreen',
				'stud_check_orientation'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }
	
	/**
     * Returns Scratch fruit game settings
     *
     * @return array
     */
    public function getScratchSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'scratch_show_credits',
				'scratch_fullscreen',
				'scratch_check_orientation',
				'scratch_bet_1',
				'scratch_bet_2',
				'scratch_bet_3',
				'scratch_prize_1',
				'scratch_prize_2',
				'scratch_prize_3',
				'scratch_prize_4',
				'scratch_prize_5',
				'scratch_prize_6',
				'scratch_prize_7',
				'scratch_prize_8',
				'scratch_prize_9',
				'scratch_win_occurence',
				'scratch_prize_probability_1',
				'scratch_prize_probability_2',
				'scratch_prize_probability_3',
				'scratch_prize_probability_4',
				'scratch_prize_probability_5',
				'scratch_prize_probability_6',
				'scratch_prize_probability_7',
				'scratch_prize_probability_8',
				'scratch_prize_probability_9',
				'scratch_win_percentage_1_rows',
				'scratch_win_percentage_2_rows',
				'scratch_win_percentage_3_rows'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }
	
	/**
     * Returns Slot Christmas game settings
     *
     * @return array
     */
    public function getSlotChristmasSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'slot_christmas_win_occurence',
				'slot_christmas_min_bet',
				'slot_christmas_max_bet',
				'slot_christmas_auto_lose_threshold',
				'slot_christmas_show_credits',
				'slot_christmas_fullscreen',
				'slot_christmas_check_orientation',
				'slot_christmas_bonus_occurence',
				'slot_christmas_maximum_hold_reels',
				'slot_christmas_bonus_occurence_1',
				'slot_christmas_bonus_occurence_2',
				'slot_christmas_bonus_occurence_3',
				'slot_christmas_paytable_1',
				'slot_christmas_paytable_2',
				'slot_christmas_paytable_3',
				'slot_christmas_paytable_4',
				'slot_christmas_paytable_5',
				'slot_christmas_paytable_6',
				'slot_christmas_paytable_7',
				'slot_christmas_paytable_8',
				'slot_christmas_bonus_1',
				'slot_christmas_bonus_2',
				'slot_christmas_bonus_3'
            )
        );
		
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }
	
	/**
     * Returns Slot chicken game settings
     *
     * @return array
     */
    public function getSlotChickenSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'slot_chicken_win_occurence',
				'slot_chicken_min_bet',
				'slot_chicken_max_bet',
				'slot_chicken_auto_lose_threshold',
				'slot_chicken_show_credits',
				'slot_chicken_fullscreen',
				'slot_chicken_check_orientation',
				'slot_chicken_bonus_occurence',
				'slot_chicken_maximum_hold_reels',
				'slot_chicken_bonus_occurence_1',
				'slot_chicken_bonus_occurence_2',
				'slot_chicken_bonus_occurence_3',
				'slot_chicken_paytable_1',
				'slot_chicken_paytable_2',
				'slot_chicken_paytable_3',
				'slot_chicken_paytable_4',
				'slot_chicken_paytable_5',
				'slot_chicken_paytable_6',
				'slot_chicken_paytable_7',
				'slot_chicken_paytable_8',
				'slot_chicken_bonus_1',
				'slot_chicken_bonus_2',
				'slot_chicken_bonus_3'
            )
        );
		
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }
	
	/**
     * Returns Slot ramses game settings
     *
     * @return array
     */
    public function getSlotRamsesSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'slot_ramses_win_occurence',
				'slot_ramses_min_bet',
				'slot_ramses_max_bet',
				'slot_ramses_auto_lose_threshold',
				'slot_ramses_show_credits',
				'slot_ramses_fullscreen',
				'slot_ramses_check_orientation',
				'slot_ramses_bonus_occurence',
				'slot_ramses_maximum_hold_reels',
				'slot_ramses_bonus_occurence_1',
				'slot_ramses_bonus_occurence_2',
				'slot_ramses_bonus_occurence_3',
				'slot_ramses_paytable_1',
				'slot_ramses_paytable_2',
				'slot_ramses_paytable_3',
				'slot_ramses_paytable_4',
				'slot_ramses_paytable_5',
				'slot_ramses_paytable_6',
				'slot_ramses_paytable_7',
				'slot_ramses_paytable_8',
				'slot_ramses_bonus_1',
				'slot_ramses_bonus_2',
				'slot_ramses_bonus_3'
            )
        );
		
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }
	
	/**
     * Returns Slot space game settings
     *
     * @return array
     */
    public function getSlotSpaceSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'slot_space_win_occurence',
				'slot_space_min_bet',
				'slot_space_max_bet',
				'slot_space_auto_lose_threshold',
				'slot_space_show_credits',
				'slot_space_fullscreen',
				'slot_space_check_orientation',
				'slot_space_bonus_occurence',
				'slot_space_maximum_hold_reels',
				'slot_space_bonus_occurence_1',
				'slot_space_bonus_occurence_2',
				'slot_space_bonus_occurence_3',
				'slot_space_paytable_1',
				'slot_space_paytable_2',
				'slot_space_paytable_3',
				'slot_space_paytable_4',
				'slot_space_paytable_5',
				'slot_space_paytable_6',
				'slot_space_paytable_7',
				'slot_space_paytable_8',
				'slot_space_bonus_1',
				'slot_space_bonus_2',
				'slot_space_bonus_3'
            )
        );
		
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }
	
	/**
     * Returns Slot fruits game settings
     *
     * @return array
     */
    public function getSlotFruitsSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'slot_fruits_win_occurence',
				'slot_fruits_min_bet',
				'slot_fruits_max_bet',
				'slot_fruits_auto_lose_threshold',
				'slot_fruits_show_credits',
				'slot_fruits_fullscreen',
				'slot_fruits_check_orientation',
				'slot_fruits_bonus_occurence',
				'slot_fruits_maximum_hold_reels',
				'slot_fruits_bonus_occurence_1',
				'slot_fruits_bonus_occurence_2',
				'slot_fruits_bonus_occurence_3',
				'slot_fruits_paytable_1',
				'slot_fruits_paytable_2',
				'slot_fruits_paytable_3',
				'slot_fruits_paytable_4',
				'slot_fruits_paytable_5',
				'slot_fruits_paytable_6',
				'slot_fruits_paytable_7',
				'slot_fruits_paytable_8',
				'slot_fruits_bonus_1',
				'slot_fruits_bonus_2',
				'slot_fruits_bonus_3'
            )
        );
		
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }

    /**
     * Returns Keno game settings
     *
     * @return array
     */
    public function getKenoSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'keno_win_occurence',
                'keno_check_orientation',
                'keno_show_credits',
                'keno_fullscreen',
                'keno_pays_2',
                'keno_pays_3',
                'keno_pays_4',
                'keno_pays_5',
                'keno_pays_6',
                'keno_pays_7',
                'keno_pays_8',
                'keno_pays_9',
                'keno_pays_10',
                'keno_occurence_2',
                'keno_occurence_3',
                'keno_occurence_4',
                'keno_occurence_5',
                'keno_occurence_6',
                'keno_occurence_7',
                'keno_occurence_8',
                'keno_occurence_9',
                'keno_occurence_10'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }

    /**
     * Returns Bingo game settings
     *
     * @return array
     */
    public function getBingoSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'bingo_win_occurence',
                'bingo_time_extraction',
                'bingo_paytable_45',
                'bingo_paytable_55',
                'bingo_paytable_65',
                'bingo_check_orientation',
                'bingo_fullscreen'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }

    /**
     * Returns Greyhound racing game settings
     *
     * @return array
     */
    public function getGreyhoundSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'greyhound_win_occurence',
                'greyhound_min_bet',
                'greyhound_max_bet',
                'greyhound_check_orientation',
                'greyhound_show_credits',
                'greyhound_fullscreen',
                'greyhound_names',
                'greyhound_odd_win',
                'greyhound_odd_place',
                'greyhound_odd_show',
                'greyhound_odd_1',
                'greyhound_odd_2',
                'greyhound_odd_3',
                'greyhound_odd_4',
                'greyhound_odd_5',
                'greyhound_odd_6'
            )
        );

        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }

    /**
     * Returns Horse racing game settings
     *
     * @return array
     */
    public function getHorseSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'horse_win_occurence',
                'horse_min_bet',
                'horse_max_bet',
                'horse_check_orientation',
                'horse_show_credits',
                'horse_fullscreen',
                'horse_names',
                'horse_odd_win',
                'horse_odd_place',
                'horse_odd_show',
                'horse_odd_1',
                'horse_odd_2',
                'horse_odd_3',
                'horse_odd_4',
                'horse_odd_5',
                'horse_odd_6',
                'horse_odd_7',
                'horse_odd_8'
            )
        );

        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }

    /**
     * Returns Slot soccer game settings
     *
     * @return array
     */
    public function getSlotSoccerSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'slot_soccer_win_occurence',
                'slot_soccer_min_bet',
                'slot_soccer_max_bet',
                'slot_soccer_auto_lose_threshold',
                'slot_soccer_show_credits',
                'slot_soccer_fullscreen',
                'slot_soccer_check_orientation',
                'slot_soccer_bonus_occurence',
                'slot_soccer_maximum_hold_reels',
                'slot_soccer_bonus_occurence_1',
                'slot_soccer_bonus_occurence_2',
                'slot_soccer_bonus_occurence_3',
                'slot_soccer_paytable_1',
                'slot_soccer_paytable_2',
                'slot_soccer_paytable_3',
                'slot_soccer_paytable_4',
                'slot_soccer_paytable_5',
                'slot_soccer_paytable_6',
                'slot_soccer_paytable_7',
                'slot_soccer_paytable_8',
                'slot_soccer_bonus_1',
                'slot_soccer_bonus_2',
                'slot_soccer_bonus_3'
            )
        );

        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }

    /**
     * Returns Slot soccer 3d game settings
     *
     * @return array
     */
    public function getSlotSoccer3dSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'slot_soccer3d_win_occurence',
                'slot_soccer3d_min_bet',
                'slot_soccer3d_max_bet',
                'slot_soccer3d_auto_lose_threshold',
                'slot_soccer3d_show_credits',
                'slot_soccer3d_fullscreen',
                'slot_soccer3d_check_orientation',
                'slot_soccer3d_bonus_occurence',
                'slot_soccer3d_bonus_occurence_1',
                'slot_soccer3d_bonus_occurence_2',
                'slot_soccer3d_bonus_occurence_3',
                'slot_soccer3d_bonus_occurence_4',
                'slot_soccer3d_bonus_occurence_5',
                'slot_soccer3d_paytable_1',
                'slot_soccer3d_paytable_2',
                'slot_soccer3d_paytable_3',
                'slot_soccer3d_paytable_4',
                'slot_soccer3d_paytable_5',
                'slot_soccer3d_paytable_6',
                'slot_soccer3d_paytable_7',
                'slot_soccer3d_paytable_8',
                'slot_soccer3d_paytable_9',
                'slot_soccer3d_paytable_10',
                'slot_soccer3d_bonus_1',
                'slot_soccer3d_bonus_2',
                'slot_soccer3d_bonus_3',
                'slot_soccer3d_bonus_4',
                'slot_soccer3d_bonus_5',
                'slot_soccer3d_freespin_occurence_1',
                'slot_soccer3d_freespin_occurence_2',
                'slot_soccer3d_freespin_occurence_3',
                'slot_soccer3d_freespin_1',
                'slot_soccer3d_freespin_2',
                'slot_soccer3d_freespin_3'
            )
        );

        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
    }

    /**
     * Returns Slot arabian game settings
     *
     * @return array
     */
    public function getSlotArabianSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'GameSetting.key' => array(
                'slot_arabian_win_occurence',
                'slot_arabian_min_bet',
                'slot_arabian_max_bet',
                'slot_arabian_auto_lose_threshold',
                'slot_arabian_show_credits',
                'slot_arabian_fullscreen',
                'slot_arabian_check_orientation',
                'slot_arabian_bonus_occurence',
                'slot_arabian_bonus_occurence_all',
                'slot_arabian_paytable_1',
                'slot_arabian_paytable_2',
                'slot_arabian_paytable_3',
                'slot_arabian_paytable_4',
                'slot_arabian_paytable_5',
                'slot_arabian_paytable_6',
                'slot_arabian_paytable_7',
                'slot_arabian_bonus',
                'slot_arabian_freespin_occurence_1',
                'slot_arabian_freespin_occurence_2',
                'slot_arabian_freespin_occurence_3',
                'slot_arabian_freespin_1',
                'slot_arabian_freespin_2',
                'slot_arabian_freespin_3'
            )
        );

        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['GameSetting']['key']] = $setting['GameSetting'];
        }
        return $list;
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
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_highlow'
                )
            ),
            1   =>  array(
                'name'      =>  __('BlackJack', true),
                'active'    =>  $params['action'] == 'admin_blackjack',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_blackjack'
                )
            ),
            2   =>  array(
                'name'      =>  __('Roulettes', true),
                'active'    =>  $params['action'] == 'admin_roulette',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_roulette'
                )
            ),
			3   =>  array(
                'name'      =>  __('Baccarat', true),
                'active'    =>  $params['action'] == 'admin_baccarat',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_baccarat'
                )
            ),
			4   =>  array(
                'name'      =>  __('Caribbean Stud', true),
                'active'    =>  $params['action'] == 'admin_stud',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_stud'
                )
            ),
			5   =>  array(
                'name'      =>  __('Scratch fruit', true),
                'active'    =>  $params['action'] == 'admin_scratch',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_scratch'
                )
            ),
			6   =>  array(
                'name'      =>  __('Slot Christmas', true),
                'active'    =>  $params['action'] == 'admin_slot_christmas',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_slot_christmas'
                )
            ),
			7   =>  array(
                'name'      =>  __('Slot chicken', true),
                'active'    =>  $params['action'] == 'admin_slot_chicken',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_slot_chicken'
                )
            ),
			8   =>  array(
                'name'      =>  __('Slot ramses', true),
                'active'    =>  $params['action'] == 'admin_slot_ramses',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_slot_ramses'
                )
            ),
			9   =>  array(
                'name'      =>  __('Slot space', true),
                'active'    =>  $params['action'] == 'admin_slot_space',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_slot_space'
                )
            ),
            10   =>  array(
                'name'      =>  __('Slot fruits', true),
                'active'    =>  $params['action'] == 'admin_slot_fruits',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_slot_fruits'
                )
            ),
            11   =>  array(
                'name'      =>  __('Keno', true),
                'active'    =>  $params['action'] == 'admin_keno',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_keno'
                )
            ),
            12   =>  array(
                'name'      =>  __('Bingo', true),
                'active'    =>  $params['action'] == 'admin_bingo',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_bingo'
                )
            ),
            13   =>  array(
                'name'      =>  __('Greyhound Racing', true),
                'active'    =>  $params['action'] == 'admin_greyhound',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_greyhound'
                )
            ),
            14   =>  array(
                'name'      =>  __('Horse Racing', true),
                'active'    =>  $params['action'] == 'admin_horse',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_horse'
                )
            ),
            15   =>  array(
                'name'      =>  __('Slot soccer', true),
                'active'    =>  $params['action'] == 'admin_slot_soccer',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_slot_soccer'
                )
            ),
            16   =>  array(
                'name'      =>  __('Slot soccer 3D', true),
                'active'    =>  $params['action'] == 'admin_slot_soccer3d',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_slot_soccer3d'
                )
            ),
            17   =>  array(
                'name'      =>  __('Slot arabian', true),
                'active'    =>  $params['action'] == 'admin_slot_arabian',
                'url'       =>  array(
                    'plugin'        =>  'casino',
                    'controller'    =>  'GameSettings',
                    'action'        =>  'admin_slot_arabian'
                )
            )
        );
    }
}