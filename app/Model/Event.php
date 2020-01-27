<?php
/**
 * Event Model
 *
 * Handles Event Data Actions
 *
 * @package    Events
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');
App::uses('TicketListener', 'Event');
App::uses('CakeEventListener', 'Event');

class Event extends AppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Event';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'bigint',
            'length'    => 20,
            'null'      => false
        ),
        'import_id'     => array(
            'type'      => 'bigint',
            'length'    => 255,
            'null'      => false
        ),
        'name'      => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'status'    => array(
            'type'      => 'int',
            'length'    => 1,
            'null'      => false
        ),
        'type'      => array(
            'type'      => 'int',
            'length'    => 1,
            'null'      => false
        ),
        'date'      => array(
            'type'      => 'string',
            'length'    => null,
            'null'      => true
        ),
        'result'    => array(
            'type'      => 'string',
            'length'    => 20,
            'null'      => false
        ),
        'active'    => array(
            'type'      => 'tinyint',
            'length'    => 1,
            'null'      => true
        ),
        'league_id' => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
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
            'message' => 'Please add event name'
        ),
        'date'  =>  array(
            'rule' => array('minLength', '2'),
            'message' => 'Please add event date'
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array('League');

    /**
     * Detailed list of hasMany associations.
     *
     * @var array
     */
    public $hasMany = array('Bet');

    /**
     * Event status not started value
     */
    const EVENT_STATUS_NOT_STARTED = 0;

    /**
     * Event status in progress value
     */
    const EVENT_STATUS_IN_PROGRESS = 1;

    /**
     * Event status finished value
     */
    const EVENT_STATUS_FINISHED = 2;

    /**
     * Event status cancelled value
     */
    const EVENT_STATUS_CANCELLED = 3;

    /**
     * Event status interrupted value
     */
    const EVENT_STATUS_INTERRUPTED = 4;

    /**
     * Event status interrupted value
     */
    const EVENT_STATUS_SUSPENDED = 5;

    /**
     * Event status unknown value
     */
    const EVENT_STATUS_UNKNOWN = 5;

    /**
     * Event status deleted value
     */
    const EVENT_STATUS_DELETED = 6;

    /**
     * Active event status value
     */
    const EVENT_ACTIVE_STATE        =   1;

    /**
     * Not active event status value
     */
    const EVENT_INACTIVE_STATE      =   0;

    /**
     * PreMatch event type value
     */
    const EVENT_TYPE_PREMATCH       =   1;

    /**
     * Live event type value
     */
    const EVENT_TYPE_LIVE           =   2;

    /**
     * Constructor. Binds the model's database table to the object.
     *
     * @param bool $id
     * @param null $table
     * @param null $ds
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new TicketListener());
    }

    /**
     * Returns admin search fields
     *
     * @return array
     */
    public function getSearch()
    {
        return array(
            'Event.name'    =>  array('type' => 'text')
        );
    }

    /**
     * Returns admin scaffold add fields
     *
     * @return array
     */
    public function getAdd($sportId = null, $leagueId = null)
    {
        $fields = array(

            "Event.sport_id"    =>  array(
                'type'      =>  'select',
                'options'   =>   $this->League->Sport->getList(),
                'default'   =>  $sportId,
                'disabled'  =>  true,
                'error'     =>  false
            ),

            "Event.league_id"   =>  array(
                'type'      =>  'select',
                'options'   =>  $this->League->getList(),
                'error'     =>  false
            ),

            'Event.name'       =>  array(
                'label'     =>  __('Event name'),
                'error'     =>  false
            ),

            'Event.date'   => array(
                'div'       =>  'control-group',
                'class'     =>  'm-ctrl-medium',
                'before'    =>  '<div class="controls">',
                'type'      =>  'text',
                'placeholder'   =>  'example: 2014-10-23 14:20',
                'after'     =>  '</div>',
                'error'     =>  false
            ),

            'Event.active'      =>  array(
                'style'     =>  '',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls"><label for="EventActive">Active</label><div class="transition-value-toggle-button">',
                'type'      =>  'checkbox',
                'class'     => 'toggle',
                'after'     =>  '</div></div>',
                'label'     =>  false,
                'error'     =>  false
            ),
        );


        if (!is_null($leagueId)) {
            $fields['Event.league_id']["default"] = $leagueId;
            $fields['Event.league_id']["disabled"] = true;
        }

        return $fields;
    }

    /**
     * Returns admin scaffold edit fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        $league = $this->League->getItem($data["Event"]["league_id"]);

        return array(

            "Event.sport_id"    =>  array(
                'type'      =>  'select',
                'options'   =>   $this->League->Sport->getList(),
                'default'   =>  $league["League"]["sport_id"],
                'disabled'  =>  true
            ),

            "Event.league_id"   =>  array(
                'type'      =>  'select',
                'options'   =>  $this->League->getList(),
                'default'   =>  $data["Event"]["league_id"],
                'disabled'  =>  true
            ),

            'Event.name'       =>  array(
                'label'     =>  __('Event name')
            ),

            'Event.date'   => array(
                'div'       =>  'control-group',
                'class'     =>  'm-ctrl-medium',
                'before'    =>  '<div class="controls">',
                'type'      =>  'text',
                'placeholder'   =>  'example: 2014-10-23 14:20',
                'after'     =>  '</div>'
            ),

            'Event.active'      =>  array(
                'style'     =>  '',
                'div'       =>  'control-group',
                'before'    =>  '<div class="controls"><label for="EventActive">Active</label><div class="transition-value-toggle-button">',
                'type'      =>  'checkbox',
                'class'     => 'toggle',
                'after'     =>  '</div></div>',
                'label'     =>  false
            ),
        );
    }

    /**
     * Returns Event By Import Id
     *
     * @param $importEventId
     *
     * @return array
     */
    public function getEventByImportId($importEventId)
    {
        return $this->find('first', array(
                'contain'       =>  array(
                    'Bet'   =>  array( 'BetPart' )
                ),
                'conditions'    =>  array(
                    'Event.import_id'   =>  $importEventId
                )
            )
        );
    }

    /**
     * Inserts | Updates Event
     *
     * @param null|int    $eventId
     * @param null|int    $importId
     * @param string      $eventName
     * @param int         $status
     * @param int         $type
     * @param string      $startDate
     * @param null|string $eventResult
     * @param int         $eventState
     * @param int         $leagueId
     * @param string      $feedType
     *
     * @return array|bool|int|mixed|string
     */
    public function saveEvent
    (
        $eventId = null,
        $importId = null,
        $eventName,
        $status = self::EVENT_STATUS_NOT_STARTED,
        $type = self::EVENT_TYPE_PREMATCH,
        $duration = null,
        $startDate,
        $lastUpdate = null,
        $eventResult = '',
        $eventState = self::EVENT_ACTIVE_STATE,
        $leagueId,
        $feedType
    )
    {
        $this->create();

        $data = array(
            'import_id'     =>  $importId,
            'name'          =>  $eventName,
            'status'        =>  $status,
            'type'          =>  $type,
            'duration'      =>  $duration,
            'date'          =>  $startDate,
            'last_update'   =>  $lastUpdate,
            'result'        =>  $eventResult,
            'active'        =>  $eventState,
            'league_id'     =>  $leagueId,
            'feed_type'     =>  $feedType
        );

        if (!is_null($eventId)) {
            $this->id = $eventId;
        }

        if (is_null($importId)) {
            unset($data['import_id']);
        }

        if ($this->hasAny(array("Event.id" => $eventId))){
            unset($data["active"]); // in case admin has disabled this event, do not update this field.
        }
        $this->save($data);

        return $this->id;
    }

    /**
     * Inserts | Updates Event
     *
     * @param       $importEventId
     * @param       $eventName
     * @param       $eventDate
     * @param       $leagueId
     * @param null  $feedType
     * @param array $importedEvent
     *
     * @deprecated Use saveEvent() instead
     *
     * @return array|bool|int|mixed|string
     */
    public function insertEvent($importEventId, $eventName, $eventDate, $leagueId, $feedType = null, $importedEvent = array())
    {
        $this->create();

        $data = array(
            'Event' =>  array(
                'import_id' =>  $importEventId,
                'name'      =>  $eventName,
                'league_id' =>  $leagueId,
                'date'      =>  $eventDate,
            )
        );

        if(is_array($importedEvent) && isset($importedEvent['Event']['id'])) {
            $this->id = $importedEvent['Event']['id'];
        }else{
            $data['Event']['active']    = self::EVENT_ACTIVE_STATE;
            $data['Event']['feed_type'] = $feedType;
        }

        $this->save($data);

        return $this->id;
    }

    /**
     * Updates event state
     *
     * @param      $eventId
     * @param int  $state
     * @param bool $byImportId
     *
     * @return bool
     */
    public function updateState($eventId, $state = 1, $byImportId = false)
    {
        $this->create();

        if($byImportId != false) {
            $EventData = $this->getEventByImportId($eventId);

            if(empty($EventData)) {
                return false;
            }

            $this->id = $EventData['Event']['id'];
        }else{
            $this->id = $eventId;
        }

        $this->save(array(
            'Event' =>  array(
                'active'    =>  $state
            )
        ));

        return true;
    }

    /**
     * Returns bets
     *
     * @param     $id
     * @param int $all
     *
     * @return array
     */
    public function getBets($id, $all = 1)
    {

        $options['conditions'] = array(
            'Event.id' => $id
        );

        $this->contain('Bet');

        $event = $this->find('first', $options);

        $bets = array();

        if (empty($event)) {
            return array();
        }

        foreach ($event['Bet'] as $bet) {
            if ($all == 0) {
                if ($this->Bet->hasTickets($bet['id'])) {
                    $bets[] = $this->Bet->getBetParts($bet['id']);
                }
            }
            else {
                $bets[] = $this->Bet->getBetParts($bet['id']);
            }
        }

        return $bets;
    }

    /**
     * Returns leagues ids
     *
     * @param      $eventsIds
     * @param bool $pending
     *
     * @return array
     */
    public function getLeaguesIds($eventsIds, $pending = false)
    {
        $options['conditions'] = array(
            'Event.id' => $eventsIds
        );

        if (!$pending) {
            $options['conditions']['Event.date <'] = gmdate('Y-m-d H:i:s');
        }

        $options['fields'] = array(
            0   =>  'Event.id',
            1   =>  'Event.league_id'
        );

        $options['group'] = 'Event.league_id';

        return $this->find('list', $options);
    }

    /**
     * Searches events by name
     *
     * @param $name
     *
     * @return array
     */
    public function findEvents($name)
    {
        return $this->find('all', array(
            'conditions'    =>  array(
                'Event.name LIKE ?'     =>  array('%' . $name . '%'),
                'Event.date > ?'        =>  array(gmdate('Y-m-d H:i:s')),
                'Event.active = ?'      =>  1
            ),
            'order'         =>  array('Event.date ASC')
        ));
    }

    /**
     * Find active event by id
     *
     * @param $id
     * @return array
     */
    public function findEvent($id)
    {
        $options['contain'] = array(
            "League",
            "Bet"       =>  array(
                "conditions"    =>  array(
                    "Bet.state" => "active"
                )
            )
        );

        $options['conditions'] = array(
            'Event.id'          => $id,
            'Event.date >'      => gmdate('Y-m-d H:i:s'),
            'Event.active = ?'      =>  1,
            'Event.type = ?'      =>  1
        );

        $options['order'] = 'Event.date ASC';

        return $this->find('first', $options);
    }

    /**
     * Find active event by id
     *
     * @param $id
     * @return array
     */
    public function findLiveEvent($id)
    {
        $options["contain"] = array(
            "League",
            "Bet" => array(
//                "conditions"    => array('Bet.state !=' => 'inactive')
            )
        );
        $options['conditions'] = array(
            'Event.id'          => $id,
//            'Event.date <='     => gmdate('Y-m-d H:i:s'),
            'Event.active = ?'      =>  1,
            'Event.type'    => 2,
            'Event.last_update >=' => gmdate('Y-m-d H:i:s', strtotime('- 60 seconds'))
        );

        $options['order'] = 'Event.date ASC';

        return $this->find('first', $options);
    }

    /**
     * @param       $eventId
     * @param array $Results
     *
     * @throws Exception
     */
    public function setResults($eventId, array $Results)
    {
        $Event = $this->find('first', array(
            'contain'       =>  array(),
            'conditions'    =>  array(
                'Event.id' => $eventId
            )
        ));

        if (empty($Event)) {
            throw new Exception("Unknown event", 500);
        }

        try {
            $data['Event']['result'] = implode(" - ", array($Results[0]["value"], $Results[1]["value"]));

            $this->save($data);

            $this->getEventManager()->dispatch(new CakeEvent('Model.Event.setResults', $this, array(
                "Event"     =>  $Event["Event"],
                "Results"   =>  $Results
            )));

        } catch (Exception $e) {
            // TODO : log
        }
    }

    /**
     * Sets event result
     *
     * @param $id
     * @param string $result
     */
    public function setResult($id, $result = '')
    {
        $data = $this->find('first', array(
            'conditions'    =>  array(
                'Event.id' => $id
            )
        ));

        $data['Event']['result'] = $result;

        $this->save($data);
    }

    function deleteEvents($leagueId) {
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
     * Returns last minute bets
     *
     * @return array
     */
    public function getLastMinuteEvents()
    {
        $Football = $this->find('all', array(
            'contain'       =>  array(
                'League'    =>  array('Sport'),
                'Bet'       =>  array(
                    'BetPart'   =>  array(
                        'conditions'    =>  array(
                            'BetPart.state'    => 'active',
                            'BetPart.odd >='    => 1
                        )
                    ),
                    'conditions'    =>  array(
                        "type"  =>  array(
                            1   =>  Bet::BET_TYPE_MATCH_RESULT
                        )
                    )
                )
            ),
            'conditions'    =>  array(
                'Event.date >'      => gmdate('Y-m-d H:i:s'),
                'Event.result'      => "",
                'Event.active'      => Event::EVENT_ACTIVE_STATE,
                "League.sport_id"   =>  Sport::SPORT_ID_FOOTBALL,

            ),
            'order'         =>  'Event.date ASC',
            'limit'         =>  7,
            'group'         =>  'Event.league_id, Event.id'
        ));

        $Basketball = $this->find('all', array(
            'contain'       =>  array(
                'League'    =>  array('Sport'),
                'Bet'       =>  array(
                    'BetPart'   =>  array(
                        'conditions'    =>  array(
                            'BetPart.state'    => 'active',
                            'BetPart.odd >='    => 1
                        )
                    ),
                    'conditions'    =>  array(
                        "type"  =>  array(
                            2   =>  Bet::BET_TYPE_MATCH_WINNER
                        )
                    )
                )
            ),
            'conditions'    =>  array(
                'Event.date >'      => gmdate('Y-m-d H:i:s'),
                'Event.active'      => Event::EVENT_ACTIVE_STATE,
                "League.sport_id"   =>  Sport::SPORT_ID_BASKETBALL

            ),
            'order'         =>  'Event.date ASC',
            'limit'         =>  7,
            'group'         =>  'Event.league_id, Event.id'
        ));

        $Tennis = $this->find('all', array(
            'contain'       =>  array(
                'League'    =>  array('Sport'),
                'Bet'       =>  array(
                    'BetPart'   =>  array(
                        'conditions'    =>  array(
                            'BetPart.state'    => 'active',
                            'BetPart.odd >='    => 1
                        )
                    ),
                    'conditions'    =>  array(
                        "type"  =>  array(
                            2   =>  Bet::BET_TYPE_MATCH_WINNER
                        )
                    )
                )
            ),
            'conditions'    =>  array(
                'Event.date >'      => gmdate('Y-m-d H:i:s'),
                'Event.active'      => Event::EVENT_ACTIVE_STATE,
                "League.sport_id"   =>  Sport::SPORT_ID_TENNIS

            ),
            'order'         =>  'Event.date ASC',
            'limit'         =>  7,
            'group'         =>  'Event.league_id, Event.id'
        ));

        $IceHockey = $this->find('all', array(
            'contain'       =>  array(
                'League'    =>  array('Sport'),
                'Bet'       =>  array(
                    'BetPart'   =>  array(
                        'conditions'    =>  array(
                            'BetPart.state'    => 'active',
                            'BetPart.odd >='    => 1
                        )
                    ),
                    'conditions'    =>  array(
                        "type"  =>  array(
                            1   =>  Bet::BET_TYPE_MATCH_RESULT
                        )
                    )
                )
            ),
            'conditions'    =>  array(
                'Event.date >'      => gmdate('Y-m-d H:i:s'),
                'Event.active'      => Event::EVENT_ACTIVE_STATE,
                "League.sport_id"   =>  Sport::SPORT_ID_ICE_HOCKEY

            ),
            'order'         =>  'Event.date ASC',
            'limit'         =>  7,
            'group'         =>  'Event.league_id, Event.id'
        ));

        foreach ($Football AS $index => $item) {
            foreach($item["Bet"] AS $betIndex => $bet) {
                if (empty($bet["BetPart"])) {
                    unset($item["Bet"][$betIndex]);
                }
            }
            if (empty($item["Bet"])) {
                unset($Football[$index]);
            }
        }

        foreach ($Basketball AS $index => $item) {
            foreach($item["Bet"] AS $betIndex => $bet) {
                if (empty($bet["BetPart"])) {
                    unset($item["Bet"][$betIndex]);
                }
            }
            if (empty($item["Bet"])) {
                unset($Basketball[$index]);
            }
        }

        foreach ($Tennis AS $index => $item) {
            foreach($item["Bet"] AS $betIndex => $bet) {
                if (empty($bet["BetPart"])) {
                    unset($item["Bet"][$betIndex]);
                }
            }
            if (empty($item["Bet"])) {
                unset($Tennis[$index]);
            }
        }

        foreach ($IceHockey AS $index => $item) {
            foreach($item["Bet"] AS $betIndex => $bet) {
                if (empty($bet["BetPart"])) {
                    unset($item["Bet"][$betIndex]);
                }
            }
            if (empty($item["Bet"])) {
                unset($IceHockey[$index]);
            }
        }

        return array(
            'Football'      =>  $this->getMarketsCount($Football),
            'Basketball'    =>  $this->getMarketsCount($Basketball),
            'Tennis'        =>  $this->getMarketsCount($Tennis),
            'IceHockey'     =>  $this->getMarketsCount($IceHockey)
        );
    }

    public function getMarketsCount($data)
    {
        foreach ($data AS $index => $item) {
            $data[$index]["Event"]["markets_count"] = $this->Bet->find('count', array(
                'conditions' => array(
                    'Event.id'  =>  $item["Event"]["id"],
                   /* "Bet.type"  =>  array(
                        0   =>  Bet::BET_TYPE_MATCH_RESULT,
                        1   =>  Bet::BET_TYPE_1X2,
                        2   =>  Bet::BET_TYPE_UNDER_OVER
                    )*/
                )
            ));
        }

        return $data;
    }

    public function getSliderEvents()
    {
        $Events = $this->find('all', array(
            'contain'       =>  array(
                'League'    =>  array('Sport'),
                'Bet'       =>  array(
                    'BetPart',
                    'conditions'    =>  array(
                        "type"  =>  array(
                            1   =>  Bet::BET_TYPE_MATCH_RESULT
                        )
                    )
                )
            ),
            'conditions'    =>  array(
                'Event.date >'      => gmdate('Y-m-d H:i:s'),
                "League.id"         =>  array(2,7,6,4)

            ),
            'order'         =>  'Event.date ASC',
            'limit'         =>  10,
            'group'         =>  'Event.league_id, Event.id'
        ));


        $options['recursive'] = -1;

        $options['fields'] = array(
            'League.id',
            'League.name',

            'Event.id',
            'Event.name',
            'Event.date',

            'Bet.id',
            'Bet.type'
        );

        $options['conditions'] = array(
            'Event.date >'  => gmdate('Y-m-d H:i:s')
        );

        $options["limit"] = 1;


        $options['joins'] = array(
            array(
                'table' => 'leagues',
                'alias' => 'League',
                'type'  => 'inner',

                'conditions' => array(
                    'League.id = Event.league_id',
                    'Event.active' => 1,
                    'Event.date >' => gmdate('Y-m-d H:i:s')
                )
            ),
            array(
                'table' => 'bets',
                'alias' => 'Bet',
                'type'  => 'inner',
                'joins' =>       array(
                    'table' => 'bet_parts',
                    'alias' => 'BetPart',
                    'type'  => 'inner',
                    'conditions' => array(
                        'Bet.id = BetPart.bet_id'
                    )
                ),
                'conditions' => array(
                    'Event.id = Bet.event_id',
                    'Bet.type'  =>  array(
                        Bet::BET_TYPE_MATCH_RESULT
                    )
                )
            ),
            array(
                'table' => 'bet_parts',
                'alias' => 'BetPart',
                'type'  => 'inner',
                'conditions' => array(
                    'Bet.id = BetPart.bet_id'
                )
            )
        );

        $result = array();

        foreach (array(3,5,8,9,10,11,12,13,14,58,2,7,6,4) AS $leagueId) {
            $options["conditions"]["League.id"] = $leagueId;
            $league = $this->find('first', $options);
//            print_r($leagueId);
//            print_r($options);
            if (empty($league)) {
                continue;
            }
            $bet = $league["Bet"];
            unset($league["Bet"]);
            $bet["BetPart"] = $this->Bet->BetPart->getBetParts($bet["id"]);
            $league["Bet"][0] = $bet;
            $result[] = $league;
            unset($league);
        }

        return array_filter($result);
    }

    /**
     * Returns last minute events count
     *
     * @param $event
     * @return array
     */
    public function countLastMinuteEvents($event)  {
        return $this->find('count', array(
            'conditions'            =>  array(
                'Event.date'        => $event['date'],
                'Event.league_id'   => $event['league_id']
            )
        ));
    }

    /**
     * Returns events list
     *
     * @param $leagueId
     * @param null $startDate
     * @param null $endDate
     * @param int $limit
     * @return array
     */
    public function getEvents($leagueId, $startDate = null, $endDate = null, $limit = null)
    {
        if ($startDate == null) {
            $startDate = gmdate('Y-m-d H:i:s');
        }

        $options = array(
            'recursive'         =>  1,
            'conditions'        =>  array(
                'Event.league_id'   =>  $leagueId,
                'Event.active'      =>  1
            ),
            'order'             =>  array('Event.date ASC')
        );

        if($endDate != null)
        {
            $options['conditions']['Event.date BETWEEN ? AND ?'] = array($startDate, $endDate);
        }else{
            $options['conditions']['Event.date >'] = $startDate;
        }

        if($limit != null) {
            $options['limit'] = $limit;
        }

        return $this->find('all', $options);
    }

    /**
     * Returns non-resulting events for 1 day
     *
     * @param string $type
     * @param null $offset
     * @param null $limit
     *
     * @return array
     */
    public function getNonResultingEvents1Day($type = 'all',  $offset = null, $limit = null)
    {
        return $this->find($type, array(
            'contain'           =>  array(),
            'recursive'         =>  -1,
            'fields'            => array('import_id'),
            'conditions'        =>  array(
                'Event.active'  =>  1,
                'Event.result'  =>  '',
            ),
            'limit'             =>  $limit,
            'offset'            =>  $offset,
            'order'             =>  array('Event.date DESC')
        ));
    }

    /**
     * Returns non-resulting events for 30 min
     *
     * @param string $type
     * @param null $offset
     * @param null $limit
     * @return array
     */
    public function getNonResultingEvents30Min($type = 'all',  $offset = null, $limit = null)
    {
        $query = array(
            'contain'           =>  array(),
            'recursive'         =>  -1,
            'fields'            =>  array('import_id'),
            'conditions'        =>  array(
                'Event.active'  =>  1,
                'Event.date >='    =>  gmdate('Y-m-d H:i:s', strtotime('-1 day')),
                'Event.date <='    =>  gmdate('Y-m-d H:i:s')
            ),
            'limit'             =>  $limit,
            'offset'            =>  $offset,
            'order'             =>  array('Event.date ASC')
        );

        if(strtolower($type) == 'count') {
            unset($query['limit']);
            unset($query['offset']);
        }

        return $this->find($type, $query);
    }

    public function getPagination()
    {
        $settings = parent::getPagination();
        $settings["contain"] = array(
            "League"    =>  array(
                "fields"    =>  array('name')
            )
        );

        return $settings;
    }

    public function getIndexEvents($leagueId, $bettingType)
    {
        $startDate = gmdate('Y-m-d H:i:s');

        $options = array(
            'recursive'         =>  1,
            'contain'           =>  array(
                'Bet'   =>  array(
                    'conditions'    =>  array(
                        'Bet.type'  => $bettingType
                    )
                ),
                'League'
            ),
            'conditions'        =>  array(
                'Event.league_id'   =>  $leagueId,
                'Event.active'      =>  1
            ),
            'order'             =>  array('Event.date ASC')
        );

        $options['conditions']['Event.date >'] = $startDate;

        $result = $this->find('all', $options);

        // containable returns empty
        foreach ($result AS  $i => $event) {
            if (empty($event["Bet"])) {
                unset($result[$i]);
            }
        }

        foreach($result AS $i => $Event)
        {
            foreach ($Event['Bet'] AS $iB => $bet) {
                $result[$i]["Bet"][$iB]["BetPart"] = $this->Bet->BetPart->getBetParts($bet['id']);
            }
        }

        return $result;
    }

    /**
     * Returns printing events
     *
     * @param $SportId
     * @param $LeagueId
     * @param $dateFrom
     * @param $dateTo
     *
     * @return array
     */
    public function getPrintingEvents($SportId, $LeagueId, $BetTypes = array(), $dateFrom, $dateTo)
    {
        return $this->find('all', array(
            'contain'   =>  array(
                'League'    =>  array(
                    'conditions' =>  array(
                        'League.sport_id'   =>  $SportId
                    )
                ),
                'Bet'       =>  array(
                    'conditions'    =>  array(
                        'Bet.type'  =>  $BetTypes,
                    )
                )
            ),
            'conditions'    =>  array(
                'Event.league_id' =>  $LeagueId,
                'Event.date BETWEEN ? AND ?'    =>  array($this->dateToStart($dateFrom), $this->dateToEnd($dateTo))
            )
        ));
    }

    public function getRangeEvents($dateFrom, $dateTo)
    {
        if (strtotime(gmdate("Y-m-d", strtotime($dateFrom))) < strtotime(gmdate("Y-m-d"))) {
            return array();
        }

        if (strtotime($dateTo) < strtotime(gmdate("Y-m-d"))) {
            return array();
        }

        $startDate = gmdate('Y-m-d', strtotime($dateFrom)) . ' ' . gmdate("H:i:s");
        $endDate =  $this->dateToEnd($dateTo);

        return $this->find('all', array(
            'conditions'    =>  array(
                'Event.active = ?'      =>  1,
                'Event.date BETWEEN ? AND ?'    =>  array($startDate, $endDate),
//                'Event.date BETWEEN ? AND ? AND Event.date >= ?'    =>  array($this->dateToStart($dateFrom), $this->dateToEnd($dateTo), gmdate('Y-m-d H:i:s', time())),
//                'Event.date <= ?'        =>  array(gmdate('Y-m-d H:i:s', strtotime('+'.Configure::read('events_start_date').' minutes'))),
            ),
            'order'     =>  array('Event.date DESC'),
        ));
    }
}