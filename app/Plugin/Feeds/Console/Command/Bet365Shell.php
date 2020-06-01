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
    public $uses = array('Feeds.Bet365', 'Country', 'Sport', 'League', 'Event', 'Bet', 'BetPart', 'Setting', 'Currency', 'Ticket', 'TicketPart');

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

//php Console/cake.php -app app Feeds.Bet365 updateDaily
    public function updateDaily()
    {
        $this->Setting->updateField('upcoming_sport_id', 0);
        $this->Event->deleteAll("DATE(date) < DATE(NOW() - INTERVAL 1 DAY)");

        $this->out('daily Done!');
    }

    //cd /var/www/html/app
    //php Console/cake.php -app app Feeds.Bet365 checkEventResult
    public function checkEventResult()
    {
        $id = '9';
        $ticket = $this->Ticket->getTicket($id);
        foreach ($ticket['TicketPart'] as $item) {
            $event = $this->Event->getItem($item['event_id']);
            $event = $this->Event->getEventByImportId($event['Event']['import_id']);
//            print_r($event);
            $this->SetResult($event);
        }
    }

    //cd /var/www/html/app
    //php Console/cake.php -app app Feeds.Bet365 checkEventResult0
    public function checkEventResult0()
    {
        $import_id = '89515484';
        $event = $this->Event->getEventByImportId($import_id);
        $eventResult = $this->Bet365->result($event['Event']['import_id']);
        $event['Event']['result'] = $eventResult->ss;
//            print_r($event);
        $this->SetResult($event);
    }

    public function SetResult($event)
    {
        $result = $event['Event']['result'];
        if ($result && strlen($result) > 2) {
            $strpos = strpos($result, ',');
            if ($strpos > -1) {
                $result = substr($result, 0, $strpos);
            }
            $strpos = strpos($result, '-');
            if ($strpos > 0) {
                $aRes = (int)(substr($result, 0, $strpos));
                $bRes = (int)(substr($result, $strpos + 1, strlen($result) - $strpos - 1));
                foreach ($event['Bet'] as $bet) {
                    $betPart = $bet['BetPart'];
                    if (sizeof($betPart) == 2) {
                        $this->__setWinLoose($betPart[0]['id'], ($aRes > $bRes));
                        $this->__setWinLoose($betPart[1]['id'], ($aRes < $bRes));
                    } else if (sizeof($betPart) == 3) {
                        $this->__setWinLoose($betPart[0]['id'], ($aRes > $bRes));
                        $this->__setWinLoose($betPart[1]['id'], ($aRes == $bRes));
                        $this->__setWinLoose($betPart[2]['id'], ($aRes < $bRes));
                    }
//                    print_r($betPart);
                }
                $this->out($result . ' ' . $aRes . '-' . $bRes);
            }
        } else {
            $this->Event->setStatus($event['Event']['id'], Event::EVENT_STATUS_FINISHED_NoResult);
        }
    }

    //cd /var/www/html/app
    //php Console/cake.php -app app Feeds.Bet365 checkResult
    public function checkResult()
    {
        $events = $this->Event->findTicketEvent();
        if (!$events) {
            $this->out('no event');
            return;
        }
        $ids='';
        for ($i=0;$i<sizeof($events);$i++){
            if ($i>0)
                $ids.=',';
            $ids.=$events[$i]['Event']['import_id'];
        }
        print_r($events);
        $eventResults = $this->Bet365->results($ids);
        for ($i=0;$i<sizeof($events);$i++){
            $events[$i]['Event']['result']=$eventResults[$i]->ss;
            $events[$i]['Event']['status']=$eventResults[$i]->time_status;
            $this->Event->save($events[$i]);
            if ($eventResults[$i]->time_status==Event::EVENT_STATUS_FINISHED){
                $this->SetResult($events[$i]);
            }
        }
//        $this->SetResult($events);
    }

    public function __setWinLoose($betPartId, $win)
    {
        $ticketsParts = $this->Ticket->getTicketsPartsByBetPartId($betPartId);
        if (!$win) {
            $status = Ticket::TICKET_STATUS_LOST;
        } else {
            $status = Ticket::TICKET_STATUS_WON;
            $this->Bet->setPick($betPartId);
        }
        foreach ($ticketsParts as $ticketPart) {
            $this->TicketPart->setStatus($ticketPart['TicketPart']['id'], $status);
        }
        //}
    }

    //cd /var/www/html/app
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
            $this->SaveLeagueAndEvent($res, $SportId, 1); //Prematch
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
                $bet_type = Bet::GetBetType($BetName);
                $BetId = $this->Bet->insertBet($BetImportId, $EventId, $BetName, $bet_type, $importedBet);

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
                $bet_order = 0;
                foreach ($Odds AS $Odd) {
                    try {
                        $betPartImportId = $Odd['id'];
//                        $this->out($betPartImportId);
                        $betPartName = isset($Odd['name']) ? $Odd['name'] : null;
                        $betPartOdd = $Odd['odds'];
                        $line = $betPartName;
                        if ($bet_type == Bet::BET_TYPE_MATCH_RESULT) {
                            $bet_order++;
                            if ($bet_order == 1)
                                $line = '1';
                            else if ($bet_order == 2)
                                $line = 'X';
                            else
                                $line = '2';
                        }

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
//        $this->Bet->setInactive($EventId, array_diff($dbIds, $sourceIds));
    }

    public function SaveLeagueAndEvent($res, $SportId, $EventType)
    {
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

        $EventDuration = null;
        if ($EventType == 2) {  //inplay
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
        if ($this->importLive != 1) {
            return;
        }
        $filters = $this->Bet365->inplay_filter();
        $results = $this->Bet365->inplay();
//        print_r($results);
        foreach ($filters AS $filter) {
            $sportImportId = $filter->sport_id;
            $SportDB = $this->Sport->getSportByImportId($sportImportId);
            if (empty($SportDB))
                continue;
            $sportId = $SportDB['Sport']['id'];
            $ImportEventId = $filter->id;  //check...
            if (!array_key_exists($ImportEventId, $results))
                continue;
            $EventId = $this->SaveLeagueAndEvent($filter, $sportId, 2); //inPlay

            $item = $results[$ImportEventId];
            $bet = $item->ma;
//            $sourceOddsIds = array();
            $BetImportId = $ImportEventId . $bet['id'];
            $BetName = $bet['name'];

            $importedBet = $this->Bet->getBetByImportId($BetImportId); //array() or a bet from list
//            $dbOddsIds = array_map(function ($bet) {
//                return $bet["import_id"];
//            }, (array)@$importedBet["BetPart"]);  //old bet_part_import_id array
            $bet_type = Bet::GetBetType($BetName);
            $BetId = $this->Bet->insertBet($BetImportId, $EventId, $BetName, $bet_type, $importedBet);
            $Odds = $item->odds;
            // Do not update odds  we can ignore this now
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
            $bet_order = 0;
            foreach ($Odds AS $Odd) {
                try {
                    $betPartImportId = $Odd['id'];
//                    $this->out($betPartImportId);
                    $betPartName = isset($Odd['name']) ? $Odd['name'] : null;
                    $betPartOdd = $Odd['odds'];
                    $line = $betPartName;
                    if ($bet_type == Bet::BET_TYPE_MATCH_RESULT) {
                        $bet_order++;
                        if ($bet_order == 1)
                            $line = '1';
                        else if ($bet_order == 2)
                            $line = 'X';
                        else
                            $line = '2';
                    }
//                    $sourceOddsIds[] = $betPartImportId;
                    $header = null;
                    $handicap = null;
                    $BetPartId = $this->BetPart->insertBetPart($betPartImportId, $betPartName, $BetId, $betPartOdd, $line, $importedBet, true, $header, $handicap);
                } catch (Exception $e) {
                    var_dump($e);
                    $this->out($Odd);
//                        var_dump($Odd);
                }
            }
//            $betPartState = (array_diff($dbOddsIds, $sourceOddsIds));
//
//            if (!empty($betPartState)) {
////                print_r($betPartState);
//                $this->Bet->BetPart->updateAll(
//                    array('BetPart.state' => "'inactive'"),   //fields to update
//                    array(
//                        "BetPart.import_id" => $betPartState
//                    )
//                );
//            }
        }
    }

}