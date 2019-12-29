<?php

App::uses('PokerAppModel', 'Poker.Model');

class PokerSetting extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'poker_settings';

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
        'setting'       => array(
            'type'      => 'string',
            'length'    => 12,
            'null'      => false
        ),
        'Xkey'     => array(
            'type'      => 'string',
            'length'    => 12,
            'null'      => false
        ),
        'Xvalue'       => array(
            'type'      => 'string',
            'length'    => 70,
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
            case 'pokerSettings':
                $db_settings = $this->getPokerSettings();
                break;

            default:
                return false;
                break;
        }

        $settings = array_map(function($setting) USE ($settings) {
            if(isset($settings['PokerSetting'][$setting['id']])) {
                return array(
                    'id'    =>  $setting['id'],
                    'Xvalue' =>  $settings['PokerSetting'][$setting['id']]
                );
            }
            return array();
        }, $db_settings);
		
		

        return $this->saveAll(array_filter(array_values($settings)));
    }

    /**
     * Returns Poker game settings
     *
     * @return array
     */
    public function getPokerSettings()
    {
		$list = array();
        $options['conditions'] = array(
            'PokerSetting.Xkey' => array(
                'TITLE',
				'APPMOD',
				'MEMMOD',
				'MOVETIMER',
				'SHOWDOWN',
				'KICKTIMER',
				'EMAILMOD',
				'DELETE',
				'WAITIMER',
				'SESSNAME',
				'RENEW',
				'DISCONNECT',
                'STAKESIZE',
                'IPCHECK'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['PokerSetting']['Xkey']] = $setting['PokerSetting'];
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
                'name'      =>  __('Poker settings', true),
                'active'    =>  $params['action'] == 'admin_settings',
                'url'       =>  array(
                    'plugin'        =>  'poker',
                    'controller'    =>  'PokerSettings',
                    'action'        =>  'admin_settings'
                )
            )
        );
    }
}