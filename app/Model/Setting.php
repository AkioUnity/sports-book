<?php
/**
 * Setting Model
 *
 * Handles Setting Data Source Actions
 *
 * @package    Settings.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Setting extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Setting';

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
     * @param $name
     * @return string
     */
    public function getValue($name)
    {
        return $this->field('value', array('key' => $name));
    }

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
            case 'generalSettings':
                $db_settings = $this->getGeneralSettings();
                break;

            case 'riskSettings':
                $db_settings = $this->getRiskSettings();
                break;

            case 'seoSettings':
                $db_settings = $this->getSeoSettings();
                break;

            case 'warningsSettings':
                $db_settings = $this->getWarningsSettings();
                break;

            case 'ticketsSettings':
                $db_settings = $this->getTicketsSettings();
                break;

            case 'depositsSettings':
                $db_settings = $this->getDepositsSettings();
                break;

            case 'depositsRisksSettings':
                $db_settings = $this->getDepositsRisksSettings();
                break;

            case 'withdrawsSettings':
                $db_settings = $this->getWithdrawsSettings();
                break;

            case 'withdrawsRisksSettings':
                $db_settings = $this->getWithdrawsRisksSettings();
                break;

            case 'referralSettings':
                $db_settings = $this->getReferralSettings();
                break;

            case 'promoSettings':
                $db_settings = $this->getPromoSettings();
                break;

            case 'depositSettings':
                $db_settings = $this->getDepositSettings();
                break;

            default:
                return false;
                break;
        }

        $settings = array_map(function($setting) USE ($settings) {
            if(isset($settings['Setting'][$setting['id']])) {
                return array(
                    'id'    =>  $setting['id'],
                    'value' =>  $settings['Setting'][$setting['id']]
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
     * Returns general settings
     *
     * @return array
     */
    public function getGeneralSettings()
    {
        $list = array();
        $keys = array(
            'websiteName',
            'registration',
            'contactMail',
            'defaultCurrency',
            'defaultTimezone',
            'defaultLanguage',
            'printing',
            'ticketPreview',
            'itemsPerPage',
            'defaultTheme',
            'contactEmail',
            'copyright',
            'referals',
            'login',
            'passwordReset',
            'allowMultiSingleBets',
            'charset',
            'show_main_event_id',
            'show_sub_event_id',
            'feedType',
            'commission',
            'service_fee',
            'weekly_balance_reset',
            'weekly_balance_reset_balance',
            'weekly_balance_reset_time',
            'weekly_balance_reset_day',
            'balance_decimal_places',
            'reservation_ticket_mode',
            'user_doc_upload',
            'user_doc_upload_email',
            'feed_import_live',
            'feed_import_prematch',
            'feed_update_odds'
        );

        $options['conditions'] = array(
            'Setting.key' => $keys
        );
        $settings = $this->find('all', $options);

        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }

        return $list;
    }

    /**
     * Returns SEO settings
     *
     * @return array
     */
    public function getSeoSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'metaDescription',
                'metaKeywords',
                'metaAuthor',
                'metaReplayTo',
                'metaCopyright',
                'metaRevisitTime',
                'metaIdentifierUrl',
                'defaultTitle'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns warnings settings
     *
     * @return array
     */
    public function getWarningsSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'bigDeposit',
                'bigWithdraw',
                'bigStake',
                'bigOdd',
                'bigWinning'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns tickets settings
     *
     * @return array
     */
    public function getTicketsSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'printing',
                'ticketPreview'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns deposit settings
     *
     * @return array
     */
    public function getDepositsSettings()
    {
        $list = array();

        $options['conditions'] = array(
            'Setting.key' => array(
                'deposits',
                'eTranzactStatus'
            )
        );

        $settings = $this->find('all', $options);

        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }

        return $list;
    }

    /**
     * Returns deposit risks settings
     *
     * @return array
     */
    public function getDepositsRisksSettings()
    {
        $list = array();

        $options['conditions'] = array(
            'Setting.key' => array(
                'deposits',
                'D_Manual',
                'deposits_bitcoin',
                'deposits_novinpal',
                'deposits_paypal',
                'deposits_skrill',
                'minDeposit',
                'maxDeposit'
            )
        );

        $settings = $this->find('all', $options);

        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }

        return $list;
    }

    /**
     * Returns withdraws settings
     *
     * @return array
     */
    public function getWithdrawsSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'withdraws'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns withdraws risks settings
     *
     * @return array
     */
    public function getWithdrawsRisksSettings()
    {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'withdraws',
                'minWithdraw',
                'maxWithdraw'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns risks settings
     *
     * @return mixed
     */
    public function getRiskSettings()
    {
        $options['conditions'] = array(
            'Setting.key' => array(
                'minBet',
                'maxBet',
                'maxBetsCount',
                'minBetsCount',
                'maxWin',
                'betBeforeEventStartDate',
                'auto_pay_wins',
                'default_stock_import',
                'ignore_passing_offline',
                'live_max_bet',
                'live_max_repeat',
                'live_max_win',
                'live_min_bet',
                'live_prematch_allow',
                'live_stock_amount',
                'max_bet_confirmtime',
                'max_bet_import',
                'max_bet_repeat',
                'region_blacklist',
                'max_bet_stock_admin',
                'max_bet_socktime',
                'max_bet_win',
                'max_daily_dp_services',
                'max_daily_wd_services',
                'max_events',
                'max_inline_odds',
                'max_system_import',
                'max_system_splitimport',
                'max_system_win',
                'max_system_win_nostock',
                'max_win_repeat',
                'min_bet_import',
                'min_playable_odd',
                'min_system_import',
                'min_system_splitimport',
                'new_market_stock_import',
                'night_bet_import_pre_match',
                'night_bet_import_live',
                'night_interval',
                'night_win_import',
                'no_bet_limit',
                'pre_stock_amount'
            )
        );

        $list = array();
        $settings = $this->find('all', $options);

        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns deposits settings
     *
     * @return array
     */
    public function getDepositSettings() {
    	$options['conditions'] = array(
    			'Setting.key' => array(
    					'deposit_funding_percentage'    					
    			)
    	);
    	$settings = $this->find('all', $options);
    	$list = array();
    	foreach ($settings as $setting) {
    		$list[$setting['Setting']['key']] = $setting['Setting'];
    	}    	
    	return $list;
    }

    /**
     * Returns referral settings
     *
     * @return array
     */
    public function getReferralSettings() {
    	$options['conditions'] = array(
    			'Setting.key' => array(
    					'referral_deposit_percentage'
    			)
    	);
    	$settings = $this->find('all', $options);
    	$list = array();
    	foreach ($settings as $setting) {
    		$list[$setting['Setting']['key']] = $setting['Setting'];
    	}
    	return $list;
    }

    /**
     * Returns promo settings
     *
     * @return array
     */
    public function getPromoSettings() {
    	$options['conditions'] = array(
    			'Setting.key' => array(    					
    					'left_promo_header',
    					'left_promo_body',
    					'right_promo_header',
    					'right_promo_body',
    					'left_promo_enabled',
    					'right_promo_enabled',
    					'bottom_promo_header',
    					'bottom_promo_body',
    					'bottom_promo_enabled'
    			)
    	);
    	$settings = $this->find('all', $options);
    	$list = array();
    	foreach ($settings as $setting) {
    		$list[$setting['Setting']['key']] = $setting['Setting'];
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
        if ($params['action'] == 'admin_warnings') {
            return array(
                0   =>  array(
                    'name'      =>  __('Warnings', true),
                    'active'    =>  $params['action'] == 'admin_warnings',
                    'url'       =>  array(
                        'plugin'        =>  null,
                        'controller'    =>  'risks',
                        'action'        =>  'admin_warnings'
                    )
                ),
                1   =>  array(
                    'name'      =>  __('Settings', true),
                    'active'    =>  $params['action'] == 'admin_warnings',
                    'url'       =>  array(
                        'plugin'        =>  null,
                        'controller'    =>  'settings',
                        'action'        =>  'admin_warnings'
                    )
                ),
            );
        }

        return array(
            0   =>  array(
                'name'      =>  __('General Settings', true),
                'active'    =>  $params['action'] == 'admin_warnings',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'settings',
                    'action'        =>  'admin_index'
                )
            ),
            1   =>  array(
                'name'      =>  __('Ticket Settings', true),
                'active'    =>  $params['action'] == 'admin_tickets',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'settings',
                    'action'        =>  'admin_tickets'
                )
            ),
            2   =>  array(
                'name'      =>  __('Currencies', true),
                'active'    =>  $params['action'] == 'admin_index',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'currencies',
                    'action'        =>  'admin_index'
                )
            ),
            3   =>  array(
                'name'      =>  __('SEO Settings', true),
                'active'    =>  $params['action'] == 'admin_index',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'settings',
                    'action'        =>  'admin_seo'
                )
            ),
            4   =>  array(
                'name'      =>  __('Email templates', true),
                'active'    =>  $params['action'] == 'admin_index',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'templates',
                    'action'        =>  'admin_index'
                )
            ),
        );
    }
}