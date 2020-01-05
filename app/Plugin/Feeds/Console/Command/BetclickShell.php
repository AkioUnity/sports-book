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

class BetClickShell extends FeedAppShell implements FeedShell
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
    const FEED_PROVIDER = 'Betclick';

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
    public $uses = array('Feeds.Betclick', 'Country', 'Sport', 'League', 'Event', 'Bet', 'BetPart', 'Setting', 'Currency', 'Ticket');

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
        if ($this->importPrematch != 1) {
            return;
        }

        $SportsXml = $this->Betclick->download(self::FEED_PROVIDER, self::FEED_PROVIDER, $this->fullXml, true);

        foreach ($SportsXml AS $Sport) {
            $SportImportId = (string)$Sport->attributes()->id;
            $SportName = (string)$Sport->attributes()->name;

            $SportId = $this->Sport->insertSport($SportImportId, $SportName, self::FEED_PROVIDER);

            $this->out('#' . $SportId . '-' . $SportImportId . ' - ' . (string)$Sport->attributes()->name);
        }
    }

    /**
     * Method which implements leagues importing / updating
     *
     * @return mixed
     */
    public function importLeagues()
    {
        if ($this->importPrematch != 1) {
            return;
        }

        $SportsXml = $this->Betclick->download(self::FEED_PROVIDER, self::FEED_PROVIDER, $this->fullXml, true);

        foreach ($SportsXml AS $Sport) {
            $SportImportId = (string)$Sport->attributes()->id;
            $SportDB = $this->Sport->getSportByImportId((string)$SportImportId);
            $SportId = !empty($SportDB) ? $SportDB['Sport']['id'] : 0;

            foreach ($Sport->event AS $League) {
                $LeagueImportId = (string)$League->attributes()->id;
                $LeagueName = (string)$League->attributes()->name;

                $LeagueId = $this->League->insertLeague($LeagueImportId, $SportId, 0, $LeagueName, self::FEED_PROVIDER);
                $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #Sport: ' . $SportId . ' - ' . $LeagueName);
            }
        }
    }

    /**
     * Method which implements events importing / updating
     *
     * @return mixed
     */
    public function importEvents()
    {
        if ($this->importPrematch != 1) {
            return;
        }

        $SportsXml = $this->Betclick->download(self::FEED_PROVIDER, self::FEED_PROVIDER, $this->fullXml, false); // because we download new file on leagues import

        foreach ($SportsXml AS $Sport) {
            $SportId = (string)$Sport->attributes()->id;

            if ($SportId != 1) {
//                continue; //@TODO
            }

            foreach ($Sport->event AS $League) {
                $LeagueImportId = (string)$League->attributes()->id;
                $LeagueDB = $this->League->getLeagueByImportId($LeagueImportId);

                if (empty($LeagueDB)) {
                    $this->out('League not found, skipping: ' . $LeagueImportId);
                    continue;
                }

                $LeagueId = $LeagueDB['League']['id'];

                if ($LeagueImportId != 3) {
//                    continue; //@TODO
                }

                if ($LeagueId != 130) {
//                    continue; // @TODO
                }

                foreach ($League->xpath('match') AS $Event) {

                    $ImportEventId = (string)$Event->attributes()->id;

                    if ($ImportEventId != 906549) {
//                        continue; // @TODO
                    }

                    $EventName = (string)$Event->attributes()->name;
                    $EventStartDate = strtotime("-1 hours", strtotime((string)$Event->attributes()->start_date));
                    $EventStartDate = date("Y-m-d H:i:s", $EventStartDate);
//                    $EventStartDate = (string)$Event->attributes()->start_date;

                    $EventStatus = Event::EVENT_STATUS_NOT_STARTED;
                    $EventType = 1; // Prematch

                    $ImportedEvent = $this->Event->getEventByImportId($ImportEventId, self::FEED_PROVIDER);

                    if (!empty($ImportedEvent)) {
                        $ImportedEvent['Bet'] = $this->Bet->getBets($ImportedEvent['Event']['id']);
                    }

                    $EventId = !empty($ImportedEvent['Event']['id']) ? $ImportedEvent['Event']['id'] : null;

                    if (is_null($EventId)) {
                        $EventId = $this->Event->saveEvent($EventId, $ImportEventId, $EventName, $EventStatus, $EventType, null, $EventStartDate, date('Y-m-d H:i:s'), $eventResult = '', Event::EVENT_ACTIVE_STATE, $LeagueId, self::FEED_PROVIDER);
                    }


                    $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #EventId: ' . $EventId . ' - ' . $ImportEventId);

                    if (!$this->Betclick->hasValidOutcomes($Event->bets)) {
                        $this->out('Event: ' . $ImportEventId . ' has not valid outcomes. Disabling Event.');
//                        $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                        continue;
                    } else {
//                        $this->Event->updateState($EventId, Event::EVENT_ACTIVE_STATE);
                    }

                    $sourceIds = array();
                    $dbIds = array_map(function($bet){ return $bet["Bet"]["import_id"]; }, (array) @$ImportedEvent["Bet"]);
                    $sourceOddsIds = array();
                    $Outcomes = $this->Betclick->getEventValidOutcomes($Event->bets);

                    foreach ($Outcomes->xpath('bet') AS $Outcome) {

                        if ((string)$Outcome->attributes()->name != "Handicap") {
//                            continue; //@TODO
                        }

                        switch ((string)$Outcome->attributes()->name) {
                            case 'Over/Under':
                                $Outcome->attributes()->name = 'Under/Over';
                                break;
                        }

                        $BetImportId = (string)($Outcome->attributes()->id . $ImportEventId);
                        $BetName = (string)$Outcome->attributes()->name;

                        $importedBet = $this->Betclick->getImportedBet($ImportedEvent, $BetImportId);
                        $dbOddsIds = array_map(function($bet){ return $bet["import_id"]; }, (array) @$importedBet["BetPart"]);
                        $BetId = $this->Bet->insertBet($BetImportId, $EventId, $BetName, $BetName, $importedBet);
                        $sourceIds[] = $BetImportId;
                        $Odds = $this->Betclick->getOutcomeValidOdds($EventName, $SportId, $BetName, $Outcome, $importedBet, $EventId);

                        if (count($Odds->choice) == 0) {
                            $EventBets = $this->Bet->getBets($EventId);
                            if (empty($EventBets)) {
//                                $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                            } else {
                                foreach ($EventBets AS $index => $EventBet) {
                                    if (empty($EventBet["Bet"]["BetPart"])) {
                                        $this->Bet->deleteBet($EventBet["Bet"]["id"]);
                                        unset($EventBets[$index]);
                                    }
                                }
                                if (empty($EventBets)) {
//                                    $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                                }
                            }
                            $this->out('#Skip Outcomes: ($Odds->count() == 0) ImportId ' . $BetName . ' ' . $ImportEventId . ' ( ' . $EventName . ' ) ' . ' - ' . (string)$Sport->attributes()->id);
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
                            $betPartImportId = (string)preg_replace("/[^0-9]/", "", $Odd->attributes()->importId);
                            $betPartName = (string)$Odd->attributes()->name;
                            $betPartOdd = (string)$Odd->attributes()->odd;
                            $line = (string)$Odd->attributes()->line;
                            $sourceOddsIds[] = $betPartImportId;

                            try {
                                $BetPartId = $this->BetPart->insertBetPart($betPartImportId, $betPartName, $BetId, $betPartOdd, $line, $importedBet);
                            } catch (Exception $e) {
                                var_dump($e);
                            }
                        }

                        $betPartState = (array_diff($dbOddsIds, $sourceOddsIds));

                        if (!empty($betPartState)) {
                            $this->Bet->BetPart->updateAll(
                                array( 'BetPart.state' =>  "'inactive'"  ),   //fields to update
                                array(
                                    "BetPart.import_id" => $betPartState
                                )
                            );
                        }
                    }

                    // not longer in source
                    $this->Bet->setInactive($EventId, array_diff($dbIds, $sourceIds));
                }
            }
        }
    }

    public function live()
    {
        $fp = fopen( TMP . __CLASS__ . __FUNCTION__ ."Lock.txt", "w+");

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

    private function liveTennis()
    {
        $xml = Xml::build($this->Betclick->curl("http://xml.cdn.betclic.com/tennis_live_gben.xml")) ;
//        $xml = Xml::build(  file_get_contents(TMP . 'xml/Betclick/tmp_t.xml') ) ;
        $this->Betclick->setLive(true); // set live mode on

        foreach($xml->live->directs AS $league)
        {
            $LeagueImportId =   (string)($league->attributes()->event);
            $LeagueDB       =   $this->League->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'League.name'  => $LeagueImportId
                )
            ));

            if(empty($LeagueDB)) {
                $LeagueName = (string)$league->attributes()->event_name;
                $LeagueId = $this->League->insertLeague($LeagueImportId, Sport::SPORT_ID_TENNIS, 0, $LeagueName, self::FEED_PROVIDER);
            } else {
                $LeagueId       =   $LeagueDB['League']['id'];
            }

            $Event          =   $league;

            $date           =   date_create_from_format("d F Y H:i", (string) $Event->attributes()->date . ' ' . date('Y') . ' ' . (string) $Event->attributes()->time);


            $ImportEventId  =   (string) $Event->attributes()->live_id;
            $EventName      =   (string) $Event->attributes()->player1Name . ' - ' . (string) $Event->attributes()->player2Name;
            $EventStartDate =   $date->format('Y-m-d H:i:s');
            $EventStatus    =   Event::EVENT_STATUS_IN_PROGRESS;
            $EventType      =   2; // Live
            $EventDuration  =   strtotime("now") - strtotime($date->format('Y-m-d H:i:s'));
            $EventScore     =   implode(" ", array_map(function($set){ return trim((string) $set->attributes()->score . ' ' . (string) $set->attributes()->points); }, $league->xpath('sets/set')));


            $ImportedEvent  =   $this->Event->getEventByImportId($ImportEventId, self::FEED_PROVIDER);

            if(!empty($ImportedEvent)) {
                $ImportedEvent['Bet'] = $this->Bet->getBets($ImportedEvent['Event']['id']);
            }

            $EventId    =   !empty($ImportedEvent['Event']['id']) ? $ImportedEvent['Event']['id'] : null;

            $EventId    =   $this->Event->saveEvent($EventId, $ImportEventId, $EventName, $EventStatus, $EventType, $EventDuration, $EventStartDate, date('Y-m-d H:i:s'), $EventScore, Event::EVENT_ACTIVE_STATE, $LeagueId, self::FEED_PROVIDER);

            $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #EventId: '  . $EventId . ' - ' . $ImportEventId);

            if(!$this->Betclick->hasValidOutcomes($Event->bets)) {
                $this->out('Event: ' . $ImportEventId . ' has not valid outcomes. Disabling Event.');
//                $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                continue;
            } else {
//                $this->Event->updateState($EventId, Event::EVENT_ACTIVE_STATE);
            }

            $Outcomes   =   $this->Betclick->getEventValidOutcomes($Event->bets);
            $sourceOddsIds = array();
            $activeBets = array();

            foreach($Outcomes->xpath('bet') AS $Outcome)
            {
                switch ((string) $Outcome->attributes()->name) {
                    case 'Over/Under':
                        $Outcome->attributes()->name = 'Under/Over';
                        break;
                }

                $prefix = explode("_", (string)$Outcome->attributes()->bet_type);
                $prefix = end($prefix);
                $prefix = str_split($prefix);


                // nera jokio garanto, kad nesidubliuos su esamais!
                $BetImportId    =   (string) (implode("", array_map(function($ch){ return ord($ch); }, $prefix)) . $ImportEventId);
                $BetName        =   (string) $Outcome->attributes()->name;

                $importedBet    =   $this->Betclick->getImportedBet($ImportedEvent, $BetImportId);
                $dbOddsIds      =   array_map(function($bet){ return $bet["import_id"]; }, (array) @$importedBet["BetPart"]);
                $BetId          =   $this->Bet->insertBet($BetImportId, $EventId, $BetName, $BetName, $importedBet);
                $activeBets[]   =   $BetId;

                $Odds           =   $this->Betclick->getOutcomeValidOdds($EventName, null, $BetName, $Outcome, $importedBet, $EventId);

                if( count( $Odds->choice )  == 0 ) {
                    $EventBets = $this->Bet->getBets($EventId);
                    if (empty($EventBets)) {
//                        $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                    }else{
                        foreach($EventBets AS $index => $EventBet) {
                            if (empty($EventBet["Bet"]["BetPart"])) {
//                                $this->Bet->deleteBet($EventBet["Bet"]["id"]);
                                unset($EventBets[$index]);
                            }
                        }
                        if (empty($EventBets)) {
//                            $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                        }
                    }
                    $this->out('#Skip Outcomes: ($Odds->count() == 0) ImportId ' . $BetName . ' ' . $ImportEventId .  ' ( ' . $EventName . ' ) ' . ' - ');
                    continue;
                }


                // Do not update odds
                if (isset($importedBet['BetPart']) && !empty($importedBet['BetPart']) && !$this->updateOdds) {
                    continue;
                }

                if(empty($importedBet)) {
                    $importedBet    =   array(
                        'Bet'   =>  array(
                            'id'        =>  $BetId,
                            'name'      =>  $BetName,
                            'event_id'  =>  $EventId,
                            'type'      =>  $BetName,
                        )
                    );
                }

                foreach ($Odds->choice AS $Odd) {
                    $betPartImportId    =   (string) $Odd->attributes()->choice_name . 2 . $BetImportId;
                    $betPartName        =   (string) $Odd->attributes()->choice_name;
                    $betPartOdd         =   (string) $Odd->attributes()->odd;
                    $line               =   (string) $Odd->attributes()->service;
                    $sourceOddsIds[]    = $betPartImportId;

                    try {
                        $BetPartId          =   $this->BetPart->insertBetPart($betPartImportId, $betPartName, $BetId, $betPartOdd, $line, $importedBet);
                    } catch (Exception $e) {
                        var_dump($e);
                        exit;
                    }
                }

                $betPartState = (array_diff($dbOddsIds, $sourceOddsIds));

                if (!empty($betPartState)) {
                    $this->Bet->BetPart->updateAll(
                        array( 'BetPart.state' =>  "'inactive'"  ),   //fields to update
                        array(
                            "BetPart.import_id" => $betPartState
                        )
                    );
                }
            }
            $this->Bet->setOnlyActive($EventId, $activeBets);
        }
    }

    private function liveFootball()
    {
        $xml = Xml::build($this->Betclick->curl("http://xml.cdn.betclic.com/football_live_gben.xml")) ;
//        $xml = Xml::build(  file_get_contents(TMP . 'xml/Betclick/tmp.xml') ) ;

        $this->Betclick->setLive(true); // set live mode on


        foreach($xml AS $league)
        {
            $LeagueImportId = intval($league->attributes()->event_id);
            $LeagueDB       =   $this->League->getLeagueByImportId( $LeagueImportId );

            if(empty($LeagueDB)) {
                $LeagueName = (string)$league->attributes()->event_name;
                $LeagueId = $this->League->insertLeague($LeagueImportId, Sport::SPORT_ID_FOOTBALL, 0, $LeagueName, self::FEED_PROVIDER);
            }else {
                $LeagueId       =   $LeagueDB['League']['id'];
            }

            foreach ($league->xpath('match') AS $Event)
            {
                $ImportEventId  =   (string) $Event->attributes()->math_id;
                $EventName      =   (string) $Event->attributes()->match_name;
                $EventStartDate =   (string) $Event->attributes()->start_date;
                $EventStatus    =   Event::EVENT_STATUS_IN_PROGRESS;
                $EventType      =   2; // Live
                $EventDuration  =   (string) $Event->attributes()->duration;
                $EventScore     =   (string) $Event->attributes()->score;

                $ImportedEvent  =   $this->Event->getEventByImportId($ImportEventId, self::FEED_PROVIDER);

                if(!empty($ImportedEvent)) {
                    $ImportedEvent['Bet'] = $this->Bet->getBets($ImportedEvent['Event']['id']);
                }

                $EventId    =   !empty($ImportedEvent['Event']['id']) ? $ImportedEvent['Event']['id'] : null;

                $EventId    =   $this->Event->saveEvent($EventId, $ImportEventId, $EventName, $EventStatus, $EventType, $EventDuration, $EventStartDate, date('Y-m-d H:i:s'), $EventScore, Event::EVENT_ACTIVE_STATE, $LeagueId, self::FEED_PROVIDER);

                $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #EventId: '  . $EventId . ' - ' . $ImportEventId);

                if(!$this->Betclick->hasValidOutcomes($Event->bets)) {
                    $this->out('Event: ' . $ImportEventId . ' has not valid outcomes. Disabling Event.');
//                    $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                    continue;
                } else {
                    // $this->Event->updateState($EventId, Event::EVENT_ACTIVE_STATE);
                }

                $Outcomes   =   $this->Betclick->getEventValidOutcomes($Event->bets);
                $sourceOddsIds = array();
                $activeBets = array();

                foreach($Outcomes->xpath('bet') AS $Outcome)
                {
                    switch ((string) $Outcome->attributes()->bet_name) {
                        case 'Over/Under':
                            $Outcome->attributes()->bet_name = 'Under/Over';
                            break;
                    }

                    $BetImportId    =   (string) ($Outcome->attributes()->bet_id . $ImportEventId);
                    $BetName        =   (string) $Outcome->attributes()->bet_name;

                    $importedBet    =   $this->Betclick->getImportedBet($ImportedEvent, $BetImportId);
                    $dbOddsIds      =   array_map(function($bet){ return $bet["import_id"]; }, (array) @$importedBet["BetPart"]);
                    $BetId          =   $this->Bet->insertBet($BetImportId, $EventId, $BetName, $BetName, $importedBet);
                    $activeBets[]   =   $BetId;

                    $Odds           =   $this->Betclick->getOutcomeValidOdds($EventName, null, $BetName, $Outcome, $importedBet, $EventId);

                    if( count( $Odds->choice )  == 0 ) {
                        $EventBets = $this->Bet->getBets($EventId);
                        if (empty($EventBets)) {
//                            $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                        }else{
                            foreach($EventBets AS $index => $EventBet) {
                                if (empty($EventBet["Bet"]["BetPart"])) {
//                                    $this->Bet->deleteBet($EventBet["Bet"]["id"]);
                                    unset($EventBets[$index]);
                                }
                            }
                            if (empty($EventBets)) {
//                                $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                            }
                        }
                        $this->out('#Skip Outcomes: ($Odds->count() == 0) ImportId ' . $BetName . ' ' . $ImportEventId .  ' ( ' . $EventName . ' ) ' . ' - ');
                        continue;
                    }

                    // Do not update odds
                    if (isset($importedBet['BetPart']) && !empty($importedBet['BetPart']) && !$this->updateOdds) {
                        continue;
                    }

                    if(empty($importedBet)) {
                        $importedBet    =   array(
                            'Bet'   =>  array(
                                'id'        =>  $BetId,
                                'name'      =>  $BetName,
                                'event_id'  =>  $EventId,
                                'type'      =>  $BetName,
                            )
                        );
                    }

                    foreach ($Odds->choice AS $Odd) {
                        $betPartImportId    =   (string) $Odd->attributes()->choice_id;
                        $betPartName        =   (string) $Odd->attributes()->choice_name;
                        $betPartOdd         =   (string) $Odd->attributes()->choice_odd;
                        $line               =   (string) $Odd->attributes()->service;
                        $sourceOddsIds[]    =   $betPartImportId;

                        try {
                            $BetPartId          =   $this->BetPart->insertBetPart($betPartImportId, $betPartName, $BetId, $betPartOdd, $line, $importedBet);
                        } catch (Exception $e) {
                            var_dump($e);
                            exit;
                        }
                    }

                    $betPartState = (array_diff($dbOddsIds, $sourceOddsIds));

                    if (!empty($betPartState)) {
                        $this->Bet->BetPart->updateAll(
                            array( 'BetPart.state' =>  "'inactive'"  ),   //fields to update
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