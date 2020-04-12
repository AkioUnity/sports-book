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
            $this->SaveLeagueAndEvent($res,$SportId,1); //Prematch
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
                        $line = $betPartName;
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

    public function SaveLeagueAndEvent($res,$SportId,$EventType){
        $league = $res->league;
        $LeagueImportId = $league->id;
        $LeagueName = $league->name;
        $LeagueId = $this->League->insertLeague($LeagueImportId, $SportId, 0, $LeagueName, self::FEED_PROVIDER);
//        $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #Sport: ' . $SportId . ' - ' . $LeagueName);
        $ImportEventId = $res->id;
        $EventName = $res->home->name . ' - ' . $res->away->name;
        $EventStartDate = date("Y-m-d H:i:s", $res->time);
//                    $EventStartDate = (string)$Event->attributes()->start_date;
        $EventStatus = $res->time_status;
        $EventScore = $res->ss;

        $ImportedEvent = $this->Event->getEventByImportId($ImportEventId, self::FEED_PROVIDER);

        if (!empty($ImportedEvent)) {
            $ImportedEvent['Bet'] = $this->Bet->getBets($ImportedEvent['Event']['id']);
        }

        $EventId = !empty($ImportedEvent['Event']['id']) ? $ImportedEvent['Event']['id'] : null;

        $EventDuration=null;
        if ($EventType==2){  //inplay
            $EventDuration = strtotime("now") - strtotime($EventStartDate);
        }

        if (is_null($EventId)) {
            $EventId = $this->Event->saveEvent($EventId, $ImportEventId, $EventName, $EventStatus, $EventType, $EventDuration, $EventStartDate, date('Y-m-d H:i:s'), $EventScore, Event::EVENT_ACTIVE_STATE, $LeagueId, self::FEED_PROVIDER);
//            $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #EventId: ' . $EventId . ' - ' . $ImportEventId);
        }

        return $EventId;
    }

//cd /var/www/html/app
//php Console/cake.php -app app Feeds.Bet365 live
    public function live()
    {
        if ($this->importLive!= 1) {
            return;
        }
        $filters = $this->Bet365->inplay_filter();
        $results= $this->Bet365->inplay();
//        print_r($results);
        foreach ($filters AS $filter) {
            $sportImportId=$filter->sport_id;
            $SportDB = $this->Sport->getSportByImportId($sportImportId);
            if (empty($SportDB))
                continue;
            $sportId = $SportDB['Sport']['id'];
            $ImportEventId =$filter->id ;  //check...
            if (!array_key_exists($ImportEventId,$results))
                continue;
            $EventId=$this->SaveLeagueAndEvent($filter,$sportId,2); //inPlay

            $ImportedEvent  =   $this->Event->getEventByImportId($ImportEventId, self::FEED_PROVIDER);

            $item=$results[$ImportEventId];
            $bet=$item->ma;
            $sourceOddsIds = array();
            $BetImportId = $ImportEventId . $bet['id'];
            $BetName = $bet['name'];

            $importedBet = $this->Bet365->getImportedBet($ImportedEvent, $BetImportId); //array() or a bet from list
            $dbOddsIds = array_map(function ($bet) {
                return $bet["import_id"];
            }, (array)@$importedBet["BetPart"]);  //old bet_part_import_id array

            $BetId = $this->Bet->insertBet($BetImportId, $EventId, $BetName, $BetName, $importedBet);

            $Odds = $item->odds;

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
//                    $this->out($betPartImportId);
                    $betPartName = isset($Odd['name'])?$Odd['name']:null;
                    $betPartOdd = $Odd['odds'];
                    $line = $betPartName;
                    $sourceOddsIds[] = $betPartImportId;
                    $header = null;
                    $handicap =null;
                    $BetPartId = $this->BetPart->insertBetPart($betPartImportId, $betPartName, $BetId, $betPartOdd, $line, $importedBet, true, $header, $handicap);
                } catch (Exception $e) {
                    var_dump($e);
                        $this->out($Odd);
//                        var_dump($Odd);
                }
            }
            $betPartState = (array_diff($dbOddsIds, $sourceOddsIds));

            if (!empty($betPartState)) {
                print_r($betPartState);
                $this->Bet->BetPart->updateAll(
                    array('BetPart.state' => "'inactive'"),   //fields to update
                    array(
                        "BetPart.import_id" => $betPartState
                    )
                );
            }
        }
    }

}