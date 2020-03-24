<?php
/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */


App::uses('FeedShell', 'Feeds.Console');
App::uses('FeedAppShell', 'Feeds.Console/Command');
App::uses('Event', 'Model');
App::uses('League', 'Model');
App::import('Utility', 'Xml');

//php Console/cake.php -app app Feeds.Bet365 main

class Bet365Shell extends FeedAppShell implements FeedShell
{
    /**
     * TimeZone Component
     *
     * @var TimeZoneComponent $TimeZone
     */
    private $TimeZone;

    /**
     * Feed Provider Name
     */
    const FEED_PROVIDER = 'Bet365';

    /**
     * Full Xml Url
     *
     * @var string
     */
    protected $fullXml = 'http://xml.cdn.betclic.com/odds_en.xml';

    protected $importPrematch;

    protected $importLive;

    protected $updateOdds;

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Feeds.Bet365', 'Country', 'Sport', 'League', 'Event', 'Bet', 'BetPart', 'Setting', 'Currency', 'Ticket');

    /**
     * Initializes the Shell
     */
    public function initialize()
    {
        $this->TimeZone = new TimeZoneComponent(new ComponentCollection());
        $this->TimeZone->initialize(new Controller());

        $this->importPrematch = $this->Setting->getValue('feed_import_prematch');
        $this->importLive = $this->Setting->getValue('feed_import_live');
        $this->updateOdds = $this->Setting->getValue('feed_update_odds');
    }

    /**
     * Main
     */
    public function main()
    {
        $this->out('It works!');
    }

    /**
     * Method which implements countries importing / updating
     *
     * @return mixed
     */
    public function importCountries()
    {
    }

    /**
     * Method which implements sports importing / updating
     *
     * @return mixed
     */
    public function importSports()
    {

    }

    //php Console/cake.php -app app Feeds.Bet365 importLeagues
    public function importLeagues()  //update leagues,events table
    {
        if ($this->importPrematch != 1) {
            return;
        }
        $sport_id = $this->Setting->getValue('upcoming_sport_id');
        $page = $this->Setting->getValue('upcoming_page');
        $this->out($sport_id . ' ' . $page);
        $data = $this->Bet365->upcoming($sport_id, $page);
        if ($data == null) {
            $this->Setting->updateField('upcoming_sport_id', 1000);
            $this->Setting->updateField('upcoming_page', 1);
            $this->out('Import Leauges Done!');
            return;
        }
        $data->pager->page++;
        $this->Setting->updateField('upcoming_page', $data->pager->page);
        $this->Setting->updateField('upcoming_sport_id', $data->sport_no);

        $SportImportId = $data->results[0]->sport_id;
        $SportDB = $this->Sport->getSportByImportId($SportImportId);
        $SportId = !empty($SportDB) ? $SportDB['Sport']['id'] : 0;

        foreach ($data->results AS $res) {
            $league = $res->league;
            $LeagueImportId = $league->id;
            $LeagueName = $league->name;
            $LeagueId = $this->League->insertLeague($LeagueImportId, $SportId, 0, $LeagueName, self::FEED_PROVIDER);
//            $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #Sport: ' . $SportId . ' - ' . $LeagueName);
            $ImportEventId = $res->id;
            $EventName = $res->home->name . ' - ' . $res->away->name;
            $EventStartDate = date("Y-m-d H:i:s", $res->time);
//                    $EventStartDate = (string)$Event->attributes()->start_date;

            $EventStatus = $res->time_status;
            $EventType = 1; // Prematch

            $ImportedEvent = $this->Event->getEventByImportId($ImportEventId, self::FEED_PROVIDER);

            if (!empty($ImportedEvent)) {
                $ImportedEvent['Bet'] = $this->Bet->getBets($ImportedEvent['Event']['id']);
            }

            $EventId = !empty($ImportedEvent['Event']['id']) ? $ImportedEvent['Event']['id'] : null;

            if (is_null($EventId)) {
                $EventId = $this->Event->saveEvent($EventId, $ImportEventId, $EventName, $EventStatus, $EventType, null, $EventStartDate, date('Y-m-d H:i:s'), $eventResult = '', Event::EVENT_ACTIVE_STATE, $LeagueId, self::FEED_PROVIDER);
            }
        }
    }
    //cd /var/www/html/app
    //php Console/cake.php -app app Feeds.Bet365 importEvents
    public function importEvents()
    {
        if ($this->importPrematch != 1) {
            return;
        }

        $EventId = $this->Setting->getValue('prematch_event_id');

        $ImportedEvent = $this->Event->getNextEvent($EventId);
        if (empty($ImportedEvent)) {
            $this->out('Import Event Done!');
            return;
        }
        $event = $ImportedEvent['Event'];
        $EventId = $event['id'];
        $ImportEventId = $event['import_id'];
        $this->out('prematch:' . $ImportEventId . '  event_id:' . $EventId);
        $this->Setting->updateField('prematch_event_id', $EventId);
        $ImportedEvent['Bet'] = $this->Bet->getBets($EventId);

        $sourceIds = array();  //curent bet_import_id array
        $dbIds = array_map(function ($bet) {
            return $bet["Bet"]["import_id"];
        }, (array)@$ImportedEvent["Bet"]);    //old bet_import_id array

        $results = $this->Bet365->prematch($ImportEventId);
        $result = $results[0];
        $values = array_values($result);
        foreach ($values as $val) {
            if (!is_array($val) || !isset($val['sp']))
                continue;
            $sps = $val['sp'];
            $bets = array_values($sps);
            foreach ($bets as $bet) {
                if (!is_array($val) || !isset($bet['name']) || !isset($bet['id']))
                    continue;

                $sourceOddsIds = array();
                $BetImportId = $ImportEventId . $bet['id'];
                $sourceIds[] = $BetImportId;

                $BetName = $bet['name'];

                $importedBet = $this->Bet365->getImportedBet($ImportedEvent, $BetImportId); //array() or a bet from list
                $dbOddsIds = array_map(function ($bet) {
                    return $bet["import_id"];
                }, (array)@$importedBet["BetPart"]);  //old bet_part_import_id array

                $BetId = $this->Bet->insertBet($BetImportId, $EventId, $BetName, $BetName, $importedBet);

                $Odds = $bet['odds'];

                // Do not update odds
                if (isset($importedBet['BetPart']) && !empty($importedBet['BetPart']) && !$this->updateOdds) {
                    continue;
                }

                if (empty($importedBet)) {
                    $importedBet = array(
                        'Bet' => array(
                            'id' => $BetId,
                            'name' => $BetName,
                            'event_id' => $EventId,
                            'type' => $BetName,
                        )
                    );
                }

                foreach ($Odds AS $Odd) {
                    try {
                        $betPartImportId = $Odd['id'];
//                        $this->out($betPartImportId);
                        $betPartName = isset($Odd['name'])?$Odd['name']:null;
                        $betPartOdd = $Odd['odds'];
                        $line = '';
                        $sourceOddsIds[] = $betPartImportId;
                        $header = (isset($Odd['header'])) ? $Odd['header'] : null;
                        $handicap = (isset($Odd['handicap'])) ? $Odd['handicap'] : null;
                        $BetPartId = $this->BetPart->insertBetPart($betPartImportId, $betPartName, $BetId, $betPartOdd, $line, $importedBet, true, $header, $handicap);
                    } catch (Exception $e) {
                        var_dump($e);
//                        $this->out($Odd);
//                        var_dump($Odd);
                    }
                }

                $betPartState = (array_diff($dbOddsIds, $sourceOddsIds));

                if (!empty($betPartState)) {
                    $this->Bet->BetPart->updateAll(
                        array('BetPart.state' => "'inactive'"),   //fields to update
                        array(
                            "BetPart.import_id" => $betPartState
                        )
                    );
                }
            }
        }
        // not longer in source
        $this->Bet->setInactive($EventId, array_diff($dbIds, $sourceIds));
    }

    public function live()
    {
        $fp = fopen(TMP . __CLASS__ . __FUNCTION__ . "Lock.txt", "w+");

        if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock

            if ($this->importLive == 1) {
                $this->liveFootball();
//                $this->liveTennis();
            }

            flock($fp, LOCK_UN); // release the lock
        } else {
            echo "Couldn't get the lock!";
        }

        fclose($fp);
    }

    private
    function liveTennis()
    {
        $xml = Xml::build($this->Betclick->curl("http://xml.cdn.betclic.com/tennis_live_gben.xml"));
//        $xml = Xml::build(  file_get_contents(TMP . 'xml/Betclick/tmp_t.xml') ) ;
        $this->Betclick->setLive(true); // set live mode on

        foreach ($xml->live->directs AS $league) {
            $LeagueImportId = (string)($league->attributes()->event);
            $LeagueDB = $this->League->find('first', array(
                'recursive' => -1,
                'conditions' => array(
                    'League.name' => $LeagueImportId
                )
            ));

            if (empty($LeagueDB)) {
                $LeagueName = (string)$league->attributes()->event_name;
                $LeagueId = $this->League->insertLeague($LeagueImportId, Sport::SPORT_ID_TENNIS, 0, $LeagueName, self::FEED_PROVIDER);
            } else {
                $LeagueId = $LeagueDB['League']['id'];
            }

            $Event = $league;

            $date = date_create_from_format("d F Y H:i", (string)$Event->attributes()->date . ' ' . date('Y') . ' ' . (string)$Event->attributes()->time);


            $ImportEventId = (string)$Event->attributes()->live_id;
            $EventName = (string)$Event->attributes()->player1Name . ' - ' . (string)$Event->attributes()->player2Name;
            $EventStartDate = $date->format('Y-m-d H:i:s');
            $EventStatus = Event::EVENT_STATUS_IN_PROGRESS;
            $EventType = 2; // Live
            $EventDuration = strtotime("now") - strtotime($date->format('Y-m-d H:i:s'));
            $EventScore = implode(" ", array_map(function ($set) {
                return trim((string)$set->attributes()->score . ' ' . (string)$set->attributes()->points);
            }, $league->xpath('sets/set')));


            $ImportedEvent = $this->Event->getEventByImportId($ImportEventId, self::FEED_PROVIDER);

            if (!empty($ImportedEvent)) {
                $ImportedEvent['Bet'] = $this->Bet->getBets($ImportedEvent['Event']['id']);
            }

            $EventId = !empty($ImportedEvent['Event']['id']) ? $ImportedEvent['Event']['id'] : null;

            $EventId = $this->Event->saveEvent($EventId, $ImportEventId, $EventName, $EventStatus, $EventType, $EventDuration, $EventStartDate, date('Y-m-d H:i:s'), $EventScore, Event::EVENT_ACTIVE_STATE, $LeagueId, self::FEED_PROVIDER);

            $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #EventId: ' . $EventId . ' - ' . $ImportEventId);

            if (!$this->Betclick->hasValidOutcomes($Event->bets)) {
                $this->out('Event: ' . $ImportEventId . ' has not valid outcomes. Disabling Event.');
//                $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                continue;
            } else {
//                $this->Event->updateState($EventId, Event::EVENT_ACTIVE_STATE);
            }

            $Outcomes = $this->Betclick->getEventValidOutcomes($Event->bets);
            $sourceOddsIds = array();
            $activeBets = array();

            foreach ($Outcomes->xpath('bet') AS $Outcome) {
                switch ((string)$Outcome->attributes()->name) {
                    case 'Over/Under':
                        $Outcome->attributes()->name = 'Under/Over';
                        break;
                }

                $prefix = explode("_", (string)$Outcome->attributes()->bet_type);
                $prefix = end($prefix);
                $prefix = str_split($prefix);


                // nera jokio garanto, kad nesidubliuos su esamais!
                $BetImportId = (string)(implode("", array_map(function ($ch) {
                        return ord($ch);
                    }, $prefix)) . $ImportEventId);
                $BetName = (string)$Outcome->attributes()->name;

                $importedBet = $this->Betclick->getImportedBet($ImportedEvent, $BetImportId);
                $dbOddsIds = array_map(function ($bet) {
                    return $bet["import_id"];
                }, (array)@$importedBet["BetPart"]);
                $BetId = $this->Bet->insertBet($BetImportId, $EventId, $BetName, $BetName, $importedBet);
                $activeBets[] = $BetId;

                $Odds = $this->Betclick->getOutcomeValidOdds($EventName, null, $BetName, $Outcome, $importedBet, $EventId);

                if (count($Odds->choice) == 0) {
                    $EventBets = $this->Bet->getBets($EventId);
                    if (empty($EventBets)) {
//                        $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                    } else {
                        foreach ($EventBets AS $index => $EventBet) {
                            if (empty($EventBet["Bet"]["BetPart"])) {
//                                $this->Bet->deleteBet($EventBet["Bet"]["id"]);
                                unset($EventBets[$index]);
                            }
                        }
                        if (empty($EventBets)) {
//                            $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                        }
                    }
                    $this->out('#Skip Outcomes: ($Odds->count() == 0) ImportId ' . $BetName . ' ' . $ImportEventId . ' ( ' . $EventName . ' ) ' . ' - ');
                    continue;
                }


                // Do not update odds
                if (isset($importedBet['BetPart']) && !empty($importedBet['BetPart']) && !$this->updateOdds) {
                    continue;
                }

                if (empty($importedBet)) {
                    $importedBet = array(
                        'Bet' => array(
                            'id' => $BetId,
                            'name' => $BetName,
                            'event_id' => $EventId,
                            'type' => $BetName,
                        )
                    );
                }

                foreach ($Odds->choice AS $Odd) {
                    $betPartImportId = (string)$Odd->attributes()->choice_name . 2 . $BetImportId;
                    $betPartName = (string)$Odd->attributes()->choice_name;
                    $betPartOdd = (string)$Odd->attributes()->odd;
                    $line = (string)$Odd->attributes()->service;
                    $sourceOddsIds[] = $betPartImportId;

                    try {
                        $BetPartId = $this->BetPart->insertBetPart($betPartImportId, $betPartName, $BetId, $betPartOdd, $line, $importedBet);
                    } catch (Exception $e) {
                        var_dump($e);
                        exit;
                    }
                }

                $betPartState = (array_diff($dbOddsIds, $sourceOddsIds));

                if (!empty($betPartState)) {
                    $this->Bet->BetPart->updateAll(
                        array('BetPart.state' => "'inactive'"),   //fields to update
                        array(
                            "BetPart.import_id" => $betPartState
                        )
                    );
                }
            }
            $this->Bet->setOnlyActive($EventId, $activeBets);
        }
    }

    private
    function liveFootball()
    {
        $xml = Xml::build($this->Betclick->curl("http://xml.cdn.betclic.com/football_live_gben.xml"));
//        $xml = Xml::build(  file_get_contents(TMP . 'xml/Betclick/tmp.xml') ) ;

        $this->Betclick->setLive(true); // set live mode on


        foreach ($xml AS $league) {
            $LeagueImportId = intval($league->attributes()->event_id);
            $LeagueDB = $this->League->getLeagueByImportId($LeagueImportId);

            if (empty($LeagueDB)) {
                $LeagueName = (string)$league->attributes()->event_name;
                $LeagueId = $this->League->insertLeague($LeagueImportId, Sport::SPORT_ID_FOOTBALL, 0, $LeagueName, self::FEED_PROVIDER);
            } else {
                $LeagueId = $LeagueDB['League']['id'];
            }

            foreach ($league->xpath('match') AS $Event) {
                $ImportEventId = (string)$Event->attributes()->math_id;
                $EventName = (string)$Event->attributes()->match_name;
                $EventStartDate = (string)$Event->attributes()->start_date;
                $EventStatus = Event::EVENT_STATUS_IN_PROGRESS;
                $EventType = 2; // Live
                $EventDuration = (string)$Event->attributes()->duration;
                $EventScore = (string)$Event->attributes()->score;

                $ImportedEvent = $this->Event->getEventByImportId($ImportEventId, self::FEED_PROVIDER);

                if (!empty($ImportedEvent)) {
                    $ImportedEvent['Bet'] = $this->Bet->getBets($ImportedEvent['Event']['id']);
                }

                $EventId = !empty($ImportedEvent['Event']['id']) ? $ImportedEvent['Event']['id'] : null;

                $EventId = $this->Event->saveEvent($EventId, $ImportEventId, $EventName, $EventStatus, $EventType, $EventDuration, $EventStartDate, date('Y-m-d H:i:s'), $EventScore, Event::EVENT_ACTIVE_STATE, $LeagueId, self::FEED_PROVIDER);

                $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #EventId: ' . $EventId . ' - ' . $ImportEventId);

                if (!$this->Betclick->hasValidOutcomes($Event->bets)) {
                    $this->out('Event: ' . $ImportEventId . ' has not valid outcomes. Disabling Event.');
//                    $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                    continue;
                } else {
                    // $this->Event->updateState($EventId, Event::EVENT_ACTIVE_STATE);
                }

                $Outcomes = $this->Betclick->getEventValidOutcomes($Event->bets);
                $sourceOddsIds = array();
                $activeBets = array();

                foreach ($Outcomes->xpath('bet') AS $Outcome) {
                    switch ((string)$Outcome->attributes()->bet_name) {
                        case 'Over/Under':
                            $Outcome->attributes()->bet_name = 'Under/Over';
                            break;
                    }

                    $BetImportId = (string)($Outcome->attributes()->bet_id . $ImportEventId);
                    $BetName = (string)$Outcome->attributes()->bet_name;

                    $importedBet = $this->Betclick->getImportedBet($ImportedEvent, $BetImportId);
                    $dbOddsIds = array_map(function ($bet) {
                        return $bet["import_id"];
                    }, (array)@$importedBet["BetPart"]);
                    $BetId = $this->Bet->insertBet($BetImportId, $EventId, $BetName, $BetName, $importedBet);
                    $activeBets[] = $BetId;

                    $Odds = $this->Betclick->getOutcomeValidOdds($EventName, null, $BetName, $Outcome, $importedBet, $EventId);

                    if (count($Odds->choice) == 0) {
                        $EventBets = $this->Bet->getBets($EventId);
                        if (empty($EventBets)) {
//                            $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                        } else {
                            foreach ($EventBets AS $index => $EventBet) {
                                if (empty($EventBet["Bet"]["BetPart"])) {
//                                    $this->Bet->deleteBet($EventBet["Bet"]["id"]);
                                    unset($EventBets[$index]);
                                }
                            }
                            if (empty($EventBets)) {
//                                $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                            }
                        }
                        $this->out('#Skip Outcomes: ($Odds->count() == 0) ImportId ' . $BetName . ' ' . $ImportEventId . ' ( ' . $EventName . ' ) ' . ' - ');
                        continue;
                    }

                    // Do not update odds
                    if (isset($importedBet['BetPart']) && !empty($importedBet['BetPart']) && !$this->updateOdds) {
                        continue;
                    }

                    if (empty($importedBet)) {
                        $importedBet = array(
                            'Bet' => array(
                                'id' => $BetId,
                                'name' => $BetName,
                                'event_id' => $EventId,
                                'type' => $BetName,
                            )
                        );
                    }

                    foreach ($Odds->choice AS $Odd) {
                        $betPartImportId = (string)$Odd->attributes()->choice_id;
                        $betPartName = (string)$Odd->attributes()->choice_name;
                        $betPartOdd = (string)$Odd->attributes()->choice_odd;
                        $line = (string)$Odd->attributes()->service;
                        $sourceOddsIds[] = $betPartImportId;

                        try {
                            $BetPartId = $this->BetPart->insertBetPart($betPartImportId, $betPartName, $BetId, $betPartOdd, $line, $importedBet);
                        } catch (Exception $e) {
                            var_dump($e);
                            exit;
                        }
                    }

                    $betPartState = (array_diff($dbOddsIds, $sourceOddsIds));

                    if (!empty($betPartState)) {
                        $this->Bet->BetPart->updateAll(
                            array('BetPart.state' => "'inactive'"),   //fields to update
                            array(
                                "BetPart.import_id" => $betPartState
                            )
                        );
                    }
                }
                $this->Bet->setOnlyActive($EventId, $activeBets);
            }
        }
    }
}