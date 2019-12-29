<?php
/**
 * League Model
 *
 * Handles League Data Source Actions
 *
 * @package    Leagues.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class League extends AppModel
{
    /**
     * League
     *
     * @var string
     */
    public $name = 'League';

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
        'name'      => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'active'    => array(
            'type'      => 'tinyint',
            'length'    => 1,
            'null'      => false
        ),
        'order'     => array(
            'type'      => 'bigint',
            'length'    => 20,
            'null'      => false
        ),
        'min_bet'   => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'max_bet'   => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'feed_type' => array(
            'type'      => 'string',
            'length'    => 10,
            'null'      => false
        )
    );

    public $validate = array(
        'name'  =>  array(
            'rule' => array('minLength', '2'),
            'message' => 'Please add league name'
        )
    );

    /** League state active value */
    const LEAGUE_STATE_ACTIVE = 1;

    /** League state inactive value */
    const LEAGUE_STATE_INACTIVE = 0;

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
    public $belongsTo = array('Country', 'Sport');

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = array('Event');

    protected $admin = false;

    /**
     * Returns list
     *
     * @return array
     */
    public function getList()
    {
        return $this->find('list', array(
            'fields'    =>  array(
                'League.id',
                'League.name'
            )
        ));
    }

    /**
     * Default pagination scaffold settings
     *
     * @return array - returns default pagination settings
     */
    public function getPagination()
    {
        return array(
            'fields'    =>  array(
                'League.id',
                'League.name',
                'League.active',
                'League.sport_id'
            ),
            'limit'     =>  Configure::read('Settings.itemsPerPage')
        );
    }

    /**
     * Returns admin scaffold add fields
     *
     * @return array
     */
    public function getAdd($sportId = null)
    {
        $fields = array(
            'League.sport_id'   =>  array(
                'type'      =>  'select',
                'options'   =>   $this->Sport->getList(),
                'error'     =>  false
            ),

//            'League.country_id' =>  array(
//                'type'      =>  'select',
//                'options'   =>   $this->Country->getList(),
//                'error'     =>  false
//            ),

            'League.name'       =>  array(
                'label' =>  __('League name'),
                'error'     =>  false
            ),

            'League.active'     =>  array(
                'style'     =>  '',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls" style="margin: 3px 5px 0 0"><label style="position: relative; top: 0px;" for="LeagueActive">Active</label><div class="transition-value-toggle-button">',
                'type'      =>  'checkbox',
                'class'     => 'toggle',
                'after'     =>  '</div></div>',
                'label'     =>  false,
                'checked'   =>  true,
                'error'     =>  false
            ),
        );

        if (!is_null($sportId)) {
            $fields['League.sport_id']["default"] = $sportId;
            $fields['League.sport_id']["disabled"] = true;
        }

        return $fields;
    }

    /**
     * Returns admin scaffold edit fields
     *
     * @return array
     */
    public function getEdit($sportId = null)
    {
        $fields = array(
            'League.sport_id'   =>  array(
                'type'      =>  'select',
                'options'   =>   $this->Sport->getList()
            ),

/*            'League.country_id' =>  array(
                'type'      =>  'select',
                'options'   =>   $this->Country->getList()
            ),*/

            'League.name'       =>  array(
                'label' =>  __('League name')
            ),



            'League.active'     =>  array(
                'style'     =>  '',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls" style="margin: 3px 5px 0 0"><label style="position: relative; top: 0px;" for="LeagueActive">Active</label><div class="transition-value-toggle-button">',
                'type'      =>  'checkbox',
                'class'     => 'toggle',
                'after'     =>  '</div></div>',
                'label'     =>  false
            ),
        );

        if (!is_null($sportId)) {
            $fields['League.sport_id']["default"] = $sportId;
            $fields['League.sport_id']["disabled"] = true;
        }

        return $fields;
    }

    /**
     * Model scaffold search fields wrapper
     *
     * @return array
     */
    public function getSearch()
    {
        return array(
            'League.name'      => array(
                'type'      => 'text',
                'label' =>  __('Sport')

            ),

            'League.active'      =>  array(
                'style'     =>  '',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls" style="margin: 3px 5px 0 0"><label style="position: relative; top: 0px;">Active</label><div class="transition-value-toggle-button">',
                'type'      =>  'checkbox',
                'class'     => 'toggle',
                'after'     =>  '</div></div>',
                'label'     =>  false
            ),

            'min_bet'   => array(
                'style'     =>  'width: 100px;',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls" style="margin-left: 5px;"><label style="position: relative; top: 0px;">Min bet</label><div class="input-prepend input-append"><span class="add-on">$</span>',
                'type'      =>  'text',
                'after'     =>  '<span class="add-on">.00</span></div></div>',
                'label'     =>  false
            ),

            'max_bet'   => array(
                'style'     =>  'width: 100px;',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls" style="margin-left: 5px;"><label style="position: relative; top: 0px;">Max bet</label><div class="input-prepend input-append"><span class="add-on">$</span>',
                'type'      =>  'text',
                'after'     =>  '<span class="add-on">.00</span></div></div>',
                'label'     =>  false
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
                'name'          => __('View events', true),
                'controller'    => null,
                'action'        => 'admin_view',
                'class'         => 'btn btn-mini'
            ),

            1   =>  array(
                'name'          => __('Edit', true),
                'controller'    => null,
                'action'        => 'admin_edit',
                'class'         => 'btn btn-mini btn-primary'
            ),

            2   =>  array(
                'name'          => __('Add Event', true),
                'controller'    => 'events',
                'action'        => 'admin_add',
                'class'         => 'btn btn-mini btn-danger'
            )
        );
    }

    /**
     * Inserts | Updates League
     *
     * @param null $importId
     * @param $sportId
     * @param $countryId
     * @param $leagueName
     * @param null $feedType
     * @param bool $update
     * @return mixed
     */
    public function insertLeague($importId = null, $sportId, $countryId, $leagueName, $feedType = null, $update = true)
    {
        $this->create();

        $data = array(
            'League'    =>  array(
//                'name'      =>  $leagueName,
                'updated'   =>  time()
            )
        );

        if($update == true) {

            $League = $this->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'League.import_id'  => $importId,
                    'League.sport_id'   => $sportId,
                    'League.feed_type'  => $feedType
                )
            ));

            if(!empty($League)) {
                $this->id = $League['League']['id'];

                $this->save($data);

                return $this->id;
            }
        }

        $LeagueData = $this->save(array(
            'League'    =>  array(
                'import_id'     =>  $importId,
                'sport_id'      =>  $sportId,
                'country_id'    =>  $countryId,
                'name'          =>  $leagueName,
                'active'        =>  1,
                'order'         =>  $this->findLastOrder() + 1,
                'feed_type'     =>  $feedType,
            )
        ), false);

        return $LeagueData['League']['id'];
    }

    /**
     * Gets sports ids
     *
     * @param $leaguesIds
     * @return array
     */
    public function getSportsIds($leaguesIds)
    {
        $options['conditions'] = array(
            'League.id' => $leaguesIds
        );
        $options['fields'] = array(
            'League.id',
            'League.sport_id'
        );
        $options['group'] = 'League.sport_id';
        return $this->find('list', $options);
    }

    /**
     * Deletes league
     *
     * @param $sportId
     */
    public function deleteLeagues($sportId)
    {
        $options['conditions'] = array(
            'League.sport_id' => $sportId
        );
        $leagues = $this->find('all', $options);
        foreach ($leagues as $league) {
            $this->Event->deleteEvents($league['league']['id']);
            //delete league
        }
    }



    /**
     * Returns League by import id
     *
     * @param $leagueImportId
     * @return array
     */
    public function getLeagueByImportId($leagueImportId)
    {
        $this->contain();

        return $this->find('first', array(
            'conditions'    =>  array(
                'League.import_id'   =>  $leagueImportId
            )
        ));
    }

    /**
     * Checks is league active
     * League is not active when no events found
     *
     * @param $league
     * @return bool
     */
    public function isActive($league)
    {
        if ($league['active'] != 1) { return false; }

        return $this->hasEvents($league['id'], true);
    }

    /**
     * Checks has league events
     *
     * @param $leagueId
     * @param null $active
     * @return bool
     */
    public function hasEvents($leagueId, $active = null)
    {
        $options['conditions'] = array(
            'Event.league_id'   =>  $leagueId,
            'Event.date >'      =>  gmdate('Y-m-d H:i:s'),
            'Event.result'      => "",
            'Event.active'      =>  1
        );

        if($active != null) {
            $options['conditions']['Event.active'] = (int) $active;
        }

        $this->Event->contain('League');

        $event = $this->Event->find('first', $options);

        if (empty($event))
            return false;
        return true;
    }

    /**
     * Returns active leagues ids by sport
     *
     * @param $sportId
     * @param $start
     * @param $end
     * @param int $limit
     * @return array
     */
    public function getActiveLeaguesIds($sportId, $start = null, $end = null, $limit = 30)
    {
        $options['recursive'] = -1;

        $options['fields'] = array(
            'League.id'
        );

        $options['conditions'] = array(
            'League.sport_id'   => $sportId
        );

        if($start != null && $end != null) {
            $options['conditions']['Event.date BETWEEN ? AND ?'] = array($start, $end);
        }

        $options['joins'] = array(
            array(
                'table' => 'events',
                'alias' => 'Event',
                'type'  => 'inner',
                'conditions' => array(
                    'League.id = Event.league_id',
                    'Event.active' => 1,
                    'Event.date >' => gmdate('Y-m-d H:i:s'),
                    'Event.result'      => "",
                )
            )
        );

        $options['group'] = array('League.id');
        $options['order'] = array('Event.date ASC');

        if(is_numeric($limit) && $limit != null) {
            $options['limit'] = $limit;
        }

        $data = $this->find('list', $options);

        return $data;
    }

    public function getLeagues($sportId, $leagueId)
    {
        $options['recursive'] = -1;

        $options['fields'] = array(
            'League.id',
            'League.name',

            'Event.id',
            'Event.type',
            'Event.name',
            'Event.date',
            'Event.last_update',

            'Bet.id',
            'Bet.type',
            'Bet.state',

            'BetPart.id',
            'BetPart.name',
            'BetPart.odd',
            'BetPart.line',
            'BetPart.order_id',
            'BetPart.state'
        );

        $options['conditions'] = array(
            'League.active'     => 1,
            'League.sport_id'   => $sportId,
        );

        if ($leagueId != null) {
            $options['conditions']["League.id"] = $leagueId;
        }

        $options['joins'] = array(
            array(
                'table' => 'events',
                'alias' => 'Event',
                'type'  => 'inner',

                'conditions' => array(
                    'League.id = Event.league_id',
                    'Event.active' => 1,
                    'Event.date >' => gmdate('Y-m-d H:i:s'),
                    'Event.result'      => "",
                )
            ),
            array(
                'table' => 'bets',
                'alias' => 'Bet',
                'type'  => 'inner',
                'conditions' => array(
                    'Event.id = Bet.event_id',
                    'Bet.type'  =>  array(
                        Bet::BET_TYPE_MATCH_RESULT,
                        Bet::BET_TYPE_MATCH_WINNER,
                        Bet::BET_TYPE_HEAD_TO_HEAD_CHAMPIONSHIP,
                        Bet::BET_TYPE_OUTRIGHT_WINNER,
                        Bet::BET_TYPE_WINNER,
                        Bet::BET_TYPE_OUTRIGHT_CHAMPION,
                        Bet::BET_TYPE_DRIVERS_WINNER,
                        Bet::BET_TYPE_CHAMPIONSHIP_WINNER,
                        Bet::BET_TYPE_PLACE_1_3,
                        Bet::BET_TYPE_DRIVERS_CHAMPIONSHIP_WINNER,
                        Bet::BET_TYPE_CONSTRUCTORS_CHAMPIONSHIP,
                        Bet::BET_TYPE_SECOND_ROUND_2_BALL // golf
                    )
                )
            ),
            array(
                'table' => 'bet_parts',
                'alias' => 'BetPart',
                'type'  => 'inner',
                'conditions' => array(
                    'Bet.id = BetPart.bet_id',
                    'BetPart.state'     =>  'active',
                    'BetPart.odd >='    =>  1
                )
            )
        );

        return $this->Event->getMarketsCount($this->find('all', $options));
    }

    public function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }

    public function getLiveLeagues($sportId = null, $leagueId = null)
    {
        if ($sportId != null) {
            $options['conditions']["League.sport_id"] = $sportId;
        }

        if ($leagueId != null) {
            $options['conditions']["League.id"] = $leagueId;
        }


        $options['fields'] = array('Event.*', 'League.*');
        $options['contain'] = array();
        $options['joins'] = array(
            array(
                'table' => 'events',
                'alias' => 'Event',
                'type'  => 'inner',

                'conditions' => array(
                    'League.id = Event.league_id',
                    'Event.active' => $this->admin ? array(0, 1) : 1,
//                    'Event.date <=' => gmdate('Y-m-d H:i:s'),
                    'Event.type'    => 2,
                    'Event.last_update >=' => gmdate('Y-m-d H:i:s', strtotime(gmdate('Y-m-d H:i:s')) - 60)
                )
            )
        );

        $leagues = array();
        foreach($this->find('all', $options) AS $data) {
            // @TODO: sport, country
            $leagues[$data["League"]["id"]]["League"] = $data["League"];
            $leagues[$data["League"]["id"]]["Event"][] = $data["Event"];
        }

        foreach ($leagues AS $lIndex => $league) {
            foreach ($league["Event"] AS $eIndex => $event) {
                $bet = $this->Event->Bet->find('all', array(
                    'contain'       =>  array('BetPart'),
                    'conditions'    =>  array(
                        'Bet.event_id'  =>  $event["id"],
                        'Bet.type'      =>   array(
                            Bet::BET_TYPE_MATCH_RESULT,
                            Bet::BET_TYPE_MATCH_WINNER,
                            Bet::BET_TYPE_HEAD_TO_HEAD_CHAMPIONSHIP,
                            Bet::BET_TYPE_OUTRIGHT_WINNER,
                            Bet::BET_TYPE_WINNER,
                            Bet::BET_TYPE_OUTRIGHT_CHAMPION,
                            Bet::BET_TYPE_DRIVERS_WINNER,
                            Bet::BET_TYPE_CHAMPIONSHIP_WINNER,
                            Bet::BET_TYPE_PLACE_1_3,
                            Bet::BET_TYPE_DRIVERS_CHAMPIONSHIP_WINNER,
                            Bet::BET_TYPE_CONSTRUCTORS_CHAMPIONSHIP,
                            Bet::BET_TYPE_SECOND_ROUND_2_BALL // golf
                        )
                    )
                ));

                $bet = (array) current($bet);

                if (isset($bet["Bet"])) {
                    $bet["Bet"]["BetPart"] = $bet["BetPart"];
                    unset($bet["BetPart"]);
                } else {
                    unset($leagues[$lIndex]);
                    continue;
                }

                $leagues[$lIndex]["Event"][$eIndex]["Bet"] = $bet;
            }
        }

        return $leagues;
    }

    /**
     * Returns active leagues
     *
     * @param $sportId
     * @return array
     */
    public function getActiveLeagues($sportId)
    {
        $options['conditions'] = array(
            'League.sport_id'   =>  $sportId
        );

        $options['order'] = 'League.name ASC';

        $options['recursive'] = -1;

        return $this->find('all', $options);
    }

    public function updateRisk($leagues)
    {
        $data = array();
        foreach ($leagues['League'] as $key => $value) {
            $value['id'] = $key;
            $data[]['League'] = $value;
        }
        return $this->saveAll($data);
    }

    /**
     * Returns Last Minute Bets
     *
     * @return mixed
     */
    public function getLastMinuteBets()
    {
        return $this->Event->getLastMinuteEvents();

        foreach ($events as $key => $value) {
            $events[$key]['Event']['count'] = $this->Event->countLastMinuteEvents($value['Event']);
        }
        return $events;
    }
}