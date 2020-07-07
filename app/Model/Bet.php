<?php
/**
 * Bet Model
 *
 * Handles Bet Data Source Actions
 *
 * @package    Bets.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Bet extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Bet';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'bigint',
            'length'    => 20,
            'null'      => false
        ),
        'import_id' =>  array(
            'type'      => 'bigint',
            'length'    => 255,
            'null'      => false
        ),
        'event_id'     => array(
            'type'      => 'bigint',
            'length'    => 20,
            'null'      => false
        ),
        'name'       => array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => false
        ),
        'type'      => array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => false
        ),
        'scope'      => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'pick'    => array(
            'type'      => 'bigint',
            'length'    => 20,
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
    public $belongsTo = array('Event');

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = array('BetPart');

    /** 12  main_1x2  */
    const BET_TYPE_MATCH_WINNER = 'Match Winner';

    /** 1x2 */
    const BET_TYPE_MATCH_RESULT = '1X2';//'Match Result';
    const TYPE_1X2=array('Fulltime Result','Full Time Result');

    const TYPE_1X2_Other=array('Extra Time Result','1st Goal - Extra Time','5th Goal');

    /** Betting type Under/Over const value */
    const BET_TYPE_UNDER_OVER = 'Under/Over';

    /** Betting type Double Chance const value */
    const BET_TYPE_DOUBLE_CHANCE = 'Double Chance';

    /** Betting type Correct Score const value */
    const BET_TYPE_CORRECT_SCORE = 'Correct Score';

    /** Betting type European Handicap const value */
    const BET_TYPE_EUROPEAN_HANDICAP = 'Handicap';

    /** Betting type Highest Scoring Half const value */
    const BET_TYPE_HIGHEST_SCORING_HALF = 'Highest Scoring Half';

    const BET_TYPE_WINNER = 'Winner';

    const BET_TYPE_OUTRIGHT_WINNER = 'Outright Winner';

    /** Betting type First Team To Score const value */
    const BET_TYPE_FIRST_TEAM_TO_SCORE = 'First Team To Score';

    /** Betting type Under/Over 1st Period const value */
    const BET_TYPE_UNDER_OVER_1ST_PERIOD = 'Under/Over 1st Period';

    /** Betting type Under/Over 2nd Period const value */
    const BET_TYPE_UNDER_OVER_2ND_PERIOD = 'Under/Over 2nd Period';

    /** Betting type Double Chance Halftime const value */
    const BET_TYPE_DOUBLE_CHANCE_HALFTIME = 'Double Chance Halftime';

    /** Betting type 12 const value */
    const BET_TYPE_AWAY_TEAM_TO_SCORE = 'Away Team to Score';

    /** Betting type Home Team to Score const value */
    const BET_TYPE_HOME_TEAM_TO_SCORE = 'Home Team to Score';

    /** Betting type Odd/Even const value */
    const BET_TYPE_ODD_EVEN = 'Odd/Even';

    /** Betting type Odd/Even Halftime const value */
    const BET_TYPE_ODD_EVEN_HALF_TIME = 'Odd/Even Halftime';

    /** Betting type 1st Period Winner const value */
    const BET_TYPE_1ST_PERIOD_WINNER = '1st Period Winner';

    /** Betting type 2nd Period Winner const value */
    const BET_TYPE_2ND_PERIOD_WINNER = '2nd Period Winner';

    /** Betting type HT/FT const value */
    const BET_TYPE_HT_FT = 'HT/FT';

    /** Betting type Both Teams To Score const value */
    const BET_TYPE_BOTH_TEAMS_TO_SCORE = 'Both Teams To Score';

    private $betOrder = array(

        1 => '1x2', // Odds_1X2
        '12', // Odds_12
        'double chance', // Odds_DoubleChance
        'under/over', //
        'first period result', // Odds_1X2
        'half-time result', // Odds_1X2
        'match winner', // Odds_12
        'time of first goal', // Odds_1X2NoName
        'first period double chance', // Odds_1X2NoName
        'total sets', // Odds_1X2NoName
        'match result', // Odds_1X2NoName
        'total goals', // fule Odds_1X2NoName, kiti Odds_UnderOver
        'second round 2 ball', // Odds_1X2NoName

        'handicap', // Odds_12NoName ( fule Odds_CorrectScore )
        'handicap result', // 12 noname,
        'sets handicap', // Odds_12NoName

        'total games', // Odds_UnderOver
        'total points', // Odds_UnderOver
        'first period total goals', // Odds_UnderOver
        'first set total points', // Odds_UnderOver

        'first team to score', // Odds_FirstTeamToScore
        'correct score', // Odds_CorrectScore

        'european handicap',
        'number of goals', // Odds_CorrectScore
        'half-time correct score', // Odds_CorrectScore
        'half-time / full-time',  // Odds_CorrectScore
        'winner', // Odds_CorrectScore
        'outright winner', // Odds_CorrectScore
        'champion',
        'place 1-3', //
        'drivers winner', // Odds_CorrectScore

    );

    public static function GetBetType($bet_name)
    {
        if (in_array($bet_name,Bet::TYPE_1X2))
            return Bet::BET_TYPE_MATCH_RESULT;
        else
            return $bet_name;
    }

    /**
     * Inserts Bet
     *
     * @param null $BetImportId
     * @param $eventId
     * @param $betName
     * @param $betType
     * @param array $importedBet
     * @return array|bool|int|mixed|string
     */
    public function insertBet($BetImportId = null, $eventId, $betName, $betType, $importedBet = array())
    {
        $this->create();

        if (is_array($importedBet) && isset($importedBet['Bet']['id'])) {
            $this->id = $importedBet['Bet']['id'];
        }

        $data = array(
            'Bet'   =>  array(
                'name'      =>  $betName,
                'event_id'  =>  $eventId,
                'type'      =>  $betType,
                'state'     =>  'active'
            )
        );

        if ($BetImportId != null) {
            $data['Bet']['import_id'] = $BetImportId;
        }

        $this->save($data);

        return $this->id;
    }

    /**
     * Delete Bet
     *
     * @param $id
     *
     * @return void
     */
    public function deleteBet($id)
    {
        $this->create();
        $this->delete($id);
    }

    /**
     * Returns bet count by event Id
     *
     * @param int $eventId - Event ID
     *
     * @return int
     */
    public function getBetPartsCount($eventId)
    {
        $betData = $this->find('first', array('conditions' => array('Bet.event_id = ' => $eventId)));

        if (empty($betData)) {
            return 0;
        }

        return count($betData['BetPart']);
    }

    /**
     * Assigns bets to events
     *
     * @param array $event - Event Array
     * @param null $betType - Bet type
     *
     * @return array
     */
    public function assignBetsToEvents($event, $betType = null)
    {
        $eventsData = array();

        $bets = $this->getBets($event['Event']['id'], $betType);

        if (empty($bets)) {
            return array();
        }

        $betsData = array();

        foreach ($bets AS $bet) {
            $betParts = $this->BetPart->getBetParts($bet['Bet']['id']);

            $betsData[$bet['Bet']['type']] = $bet['Bet'];

            $betsData[$bet['Bet']['type']]['BetPart'] = $betParts;
        }

        $eventsData[$event['Event']['id']]['Event'] = $event['Event'];

        $eventsData[$event['Event']['id']]['Bet'] = $betsData;

        $data['Event'] = $eventsData;

        return $data;
    }

    /**
     * Returns event bets
     *
     * @param $eventId
     * @param null $betType
     * @param null $oddsCount
     * @return array
     */
    public function getBets($eventId, $betType = null, $options = array())
    {
        $options['conditions'] = array(
            'Bet.event_id'  =>  $eventId
        ) + $options;

        if (isset($betType)) {
            $betType = str_replace('_', '/', $betType);
            $betType = str_replace('-', ' ', $betType);
            $options['conditions']['Bet.type'] = array($betType);
        }

        $this->contain('BetPart');

        $options['recursive'] = 1;

        $data = $this->find('all', $options);

        return $data;
    }

    public function getBetByImportId($importId)
    {
        $options['conditions'] = array(
            'Bet.import_id' => $importId
        );
        $this->contain('BetPart');
        $bet = $this->find('first', $options);
        return $bet;
    }

    public function getBetParts($id) {
        $options['conditions'] = array(
            'Bet.id' => $id
        );
        $this->contain('BetPart');
        $bet = $this->find('first', $options);
        return $bet;
    }

    public function setPick($betPartId) {
        $betPart = $this->BetPart->getItem($betPartId);
        $options['conditions'] = array(
            'Bet.id' => $betPart['BetPart']['bet_id']
        );
        $data = $this->find('first', $options);
        $data['Bet']['pick'] = $betPartId;
        $this->save($data);
    }

    public function hasTickets($id) {
        $options['conditions'] = array(
            'BetPart.bet_id' => $id
        );
        $betParts = $this->BetPart->getBetPartsIds($id);
        App::import('model', 'TicketPart');
        $TicketPart = new TicketPart();
        $options['conditions'] = array(
            'TicketPart.bet_part_id' => $betParts
        );
        $ticketPart = $TicketPart->find('first', $options);
        if (empty($ticketPart))
            return false;
        return true;
    }

    /**
     * Returns scaffold add fields
     *
     * @return array
     */
    public function getAdd()
    {
        $fields = array(
            'Bet.name',
            'Bet.type'
        );
        return $fields;
    }

    /**
     * Get item wrapper
     *
     * @param int   $id        - Item Id
     * @param array $contain   - Contain array
     * @param int   $recursive - Recursive
     *
     * @return array
     */
    public function getItem($id, $recursive = 1, $contain = null) {
        $options['conditions'] = array(
            'Bet.id' => $id
        );
        $options['recursive'] = $recursive;
        $data = $this->find('first', $options);
        return $data;
    }

    public function getEventsIds($betsIds) {
        $options['conditions'] = array(
            'Bet.id' => $betsIds
        );
        $options['fields'] = array(
            'Bet.id',
            'Bet.event_id'
        );
        $options['group'] = 'Bet.event_id';
        return $this->find('list', $options);
    }

    /**
     * Return odds types
     *
     * @return array
     */
    public function getOddsTypes()
    {
        return array(
            0   =>  'Decimal',
            2   =>  'Fractional',
            3   =>  'American'
        );
    }

    public function betToPath($betType)
    {
        $betType = str_replace('_', '-', $betType);
        $betType = str_replace('(', '', $betType);
        $betType = str_replace(')', '', $betType);
        $betType = str_replace('/', '-', $betType);
        return ucfirst(str_replace(' ', '-', $betType));
    }

    /**
     * Returns main betting type by sports ids
     *
     * @param array $SportsIds
     *
     * @return array
     */
    public function getMainBetTypesForSports($SportsIds = array())
    {
        $sportsMap = array(
            Sport::SPORT_TYPE_FOOTBALL          =>  Bet::BET_TYPE_MATCH_RESULT,  Sport::SPORT_TYPE_ICE_HOCKEY        =>  Bet::BET_TYPE_MATCH_RESULT,
            Sport::SPORT_TYPE_FLOORBALL         =>  Bet::BET_TYPE_MATCH_RESULT,  Sport::SPORT_TYPE_HANDBALL          =>  Bet::BET_TYPE_MATCH_RESULT,
            Sport::SPORT_TYPE_BANDY             =>  Bet::BET_TYPE_MATCH_RESULT,  Sport::SPORT_TYPE_BASKETBALL        =>  Bet::BET_TYPE_MATCH_WINNER,
            Sport::SPORT_TYPE_TENNIS            =>  Bet::BET_TYPE_MATCH_WINNER,   Sport::SPORT_TYPE_AMERICAN_FOOTBALL =>  Bet::BET_TYPE_MATCH_WINNER,
            Sport::SPORT_TYPE_VOLLEYBALL        =>  Bet::BET_TYPE_MATCH_WINNER,   Sport::SPORT_TYPE_BASEBALL          =>  Bet::BET_TYPE_MATCH_WINNER,
            Sport::SPORT_TYPE_BOXING            =>  Bet::BET_TYPE_MATCH_RESULT,  Sport::SPORT_TYPE_DARTS             =>  Bet::BET_TYPE_MATCH_WINNER,
            Sport::SPORT_TYPE_BADMINTON         =>  Bet::BET_TYPE_MATCH_WINNER,   Sport::SPORT_TYPE_MOTOR_SPORTS      =>  Bet::BET_TYPE_MATCH_WINNER,
            Sport::SPORT_TYPE_ALPINE_SKIING     =>  Bet::BET_TYPE_MATCH_WINNER,   Sport::SPORT_TYPE_SNOOKER           =>  Bet::BET_TYPE_MATCH_WINNER,
            Sport::SPORT_TYPE_TABLE_TENNIS      =>  Bet::BET_TYPE_MATCH_WINNER,   Sport::SPORT_TYPE_RUGBY_UNION       =>  Bet::BET_TYPE_MATCH_RESULT,
            Sport::SPORT_TYPE_RUGBY_LEAGUE      =>  Bet::BET_TYPE_MATCH_RESULT,  Sport::SPORT_TYPE_EQUINE_SPORTS     =>  Bet::BET_TYPE_MATCH_WINNER,
            Sport::SPORT_TYPE_CURLING           =>  Bet::BET_TYPE_MATCH_WINNER,   Sport::SPORT_TYPE_WATERPOLO         =>  Bet::BET_TYPE_MATCH_RESULT,
            Sport::SPORT_TYPE_AUSTRALIAN_RULES  =>  Bet::BET_TYPE_MATCH_RESULT,  Sport::SPORT_TYPE_CRICKET           =>  Bet::BET_TYPE_MATCH_WINNER,
            Sport::SPORT_TYPE_HOCKEY            =>  Bet::BET_TYPE_MATCH_RESULT,  Sport::SPORT_TYPE_BEACH_VOLLEYBALL  =>  Bet::BET_TYPE_MATCH_WINNER,
            Sport::SPORT_TYPE_FUTSAL            =>  Bet::BET_TYPE_MATCH_RESULT,  Sport::SPORT_TYPE_HORSE_RACING      =>  Bet::BET_TYPE_MATCH_WINNER
        );

        if(!is_array($SportsIds)) {
            return array();
        }

        if(is_array($SportsIds) && empty($SportsIds)) {
            return $sportsMap;
        }

        $result = array();

        foreach($SportsIds AS $sportId) {
            if(isset($sportsMap[$sportId])) {
                $result[$sportId] = $sportsMap[$sportId];
            }
        }

        return array_values($result);
    }

    public function getPrintingsBets($sportId)
    {
        $mainBetTypesForSports = $this->getMainBetTypesForSports();

        $AvailableBets      = array(
            Bet::BET_TYPE_MATCH_RESULT   =>  array(
                'title'     =>  '1X2',
                'type'      =>  Bet::BET_TYPE_MATCH_RESULT,
                'header'    =>  array('1', 'X', '2'),
                'body'      =>  array('-', '-', '-')
            ),
            Bet::BET_TYPE_MATCH_WINNER   =>  array(
                'title'     =>  '12',
                'type'      =>  Bet::BET_TYPE_MATCH_WINNER,
                'header'    =>  array('1', '2'),
                'body'      =>  array('-', '-')
            ),
            Bet::BET_TYPE_EUROPEAN_HANDICAP   =>  array(
                'title'     =>  __('Handicap'),
                'type'      =>  Bet::BET_TYPE_EUROPEAN_HANDICAP,
                'header'    =>  array('arg', '1', 'X', '2'),
                'body'      =>  array('-', '-', '-', '-')
            ),
            Bet::BET_TYPE_DOUBLE_CHANCE   =>  array(
                'title'     =>  __('Double Chance'),
                'type'      =>  Bet::BET_TYPE_DOUBLE_CHANCE,
                'header'    =>  array('1X', '12', 'X2'),
                'body'      =>  array('-', '-', '-')
            ),
            Bet::BET_TYPE_DOUBLE_CHANCE_HALFTIME   =>  array(
                'title'     =>  __('Double Chance Halftime'),
                'type'      =>  Bet::BET_TYPE_DOUBLE_CHANCE_HALFTIME,
                'header'    =>  array('1X', '12', 'X2'),
                'body'      =>  array('-', '-', '-')
            ),
            Bet::BET_TYPE_HOME_TEAM_TO_SCORE   =>  array(
                'title'     =>  __('Home to Score'),
                'type'      =>  Bet::BET_TYPE_HOME_TEAM_TO_SCORE,
                'header'    =>  array(__('Yes'), __('No')),
                'body'      =>  array('-', '-')
            ),
            Bet::BET_TYPE_AWAY_TEAM_TO_SCORE   =>  array(
                'title'     =>  __('Away to Score'),
                'type'      =>  Bet::BET_TYPE_AWAY_TEAM_TO_SCORE,
                'header'    =>  array(__('Yes'), __('No')),
                'body'      =>  array('-', '-')
            ),
            Bet::BET_TYPE_UNDER_OVER   =>  array(
                'title'     =>  __('Under/Over'),
                'type'      =>  Bet::BET_TYPE_UNDER_OVER,
                'header'    =>  array('<2.5', '>2.5'),
                'body'      =>  array('-', '-')
            ),
            Bet::BET_TYPE_HT_FT   =>  array(
                'title'     =>  __('Halftime/Fulltime'),
                'type'      =>  Bet::BET_TYPE_HT_FT,
                'header'    =>  array('1/1', '2/1', 'X/1', '1/X', '2/X', '1/2', 'X/2', '2/2', 'X/X'),
                'body'      =>  array('-', '-', '-','-', '-', '-','-', '-', '-')
            )
        );

        switch($mainBetTypesForSports[$sportId]) {
            case Bet::BET_TYPE_MATCH_RESULT:
                $BetTypesBySport = array(Bet::BET_TYPE_MATCH_RESULT, Bet::BET_TYPE_EUROPEAN_HANDICAP, Bet::BET_TYPE_UNDER_OVER, Bet::BET_TYPE_DOUBLE_CHANCE, Bet::BET_TYPE_MATCH_WINNER, Bet::BET_TYPE_HT_FT, Bet::BET_TYPE_DOUBLE_CHANCE_HALFTIME);
                break;
            case Bet::BET_TYPE_MATCH_WINNER:
                $BetTypesBySport = array(Bet::BET_TYPE_MATCH_WINNER, Bet::BET_TYPE_EUROPEAN_HANDICAP, Bet::BET_TYPE_UNDER_OVER, Bet::BET_TYPE_ODD_EVEN);
                break;
            default:
                throw new Exception(__('Bet type " %s " is not implemented for printing.', $sportId), 500);
                break;
        }


        foreach($BetTypesBySport AS $index => $sportBetType) {
            unset($BetTypesBySport[$index]);
            if(!isset($AvailableBets[$sportBetType])) {
                continue;
            }
            $BetTypesBySport[$sportBetType] = $AvailableBets[$sportBetType];
            $BetTypesBySport[$sportBetType]['order'] = ++$index;
        }
        return $BetTypesBySport;
    }

    public function sortBet($a, $b)
    {
         $aK = array_search(strtolower($a["type"]), $this->betOrder);
         $bK = array_search(strtolower($b["type"]), $this->betOrder);

        if ($aK == $bK) {
            return 0;
        }

        return ($aK > $bK) ? +1 : -1;
    }

    public function getBetTypes()
    {
        return array(
            0   =>  Bet::BET_TYPE_MATCH_WINNER,
            1   =>  Bet::BET_TYPE_MATCH_RESULT,
            2   =>  Bet::BET_TYPE_DOUBLE_CHANCE,
            3   =>  Bet::BET_TYPE_DOUBLE_CHANCE_HALFTIME,
            4   =>  Bet::BET_TYPE_UNDER_OVER,
            5   =>  Bet::BET_TYPE_UNDER_OVER_1ST_PERIOD,
            6   =>  Bet::BET_TYPE_UNDER_OVER_2ND_PERIOD,
            7   =>  Bet::BET_TYPE_ODD_EVEN,
            8   =>  Bet::BET_TYPE_ODD_EVEN_HALF_TIME,
            9   =>  Bet::BET_TYPE_CORRECT_SCORE,
            10  =>  Bet::BET_TYPE_EUROPEAN_HANDICAP,
            11  =>  Bet::BET_TYPE_FIRST_TEAM_TO_SCORE,
            12  =>  Bet::BET_TYPE_HIGHEST_SCORING_HALF,
            13  =>  Bet::BET_TYPE_HT_FT,
            14  =>  Bet::BET_TYPE_HOME_TEAM_TO_SCORE,
            15  =>  Bet::BET_TYPE_AWAY_TEAM_TO_SCORE,
            16  =>  Bet::BET_TYPE_BOTH_TEAMS_TO_SCORE,
            17  =>  Bet::BET_TYPE_1ST_PERIOD_WINNER,
            18  =>  Bet::BET_TYPE_2ND_PERIOD_WINNER,
        );
    }

    /**
     * Main purpose is to disable no longer provided odds in feed
     *
     * @param $eventId
     *
     * @param array $betsIds
     */
    public function setOnlyActive($eventId, array $betsIds)
    {
        $this->updateAll(
            array( 'Bet.state' =>  "'inactive'"  ),   //fields to update
            array(
                'Bet.event_id' => $eventId,
                'NOT' => array( "Bet.id" => $betsIds )
            )
        );
    }

    /**
     * Main purpose is to disable no longer provided odds in feed
     *
     * @param $eventId
     *
     * @param array $betsIds
     */
    public function setInactive($eventId, array $betsIds)
    {
        $this->updateAll(
            array( 'Bet.state' =>  "'inactive'"  ),   //fields to update
            array(
                'Bet.event_id' => $eventId,
                "Bet.import_id" => $betsIds
            )
        );
    }
}