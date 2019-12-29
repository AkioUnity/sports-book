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
App::import('Vendor', 'OddService', array('file' => 'OddService/OddServiceXml.php'));
App::import('Utility', 'Xml');

class OddServiceShell extends FeedAppShell implements FeedShell
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
    const FEED_PROVIDER = 'OddService';

    /**
     * Event status finished value
     */
    const EVENT_STATUS_FINISHED = 'finished';

    /**
     * Full Xml Url
     *
     * @var string
     */
    protected $fullXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSportEvents?email=offerscenter@gmail.com&password=dflkjh3497dj&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4&bookmakers=7';

    /**
     * Countries Xml Url
     *
     * @var string
     */
    protected $countriesXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetCountries?email=offerscenter@gmail.com&password=dflkjh3497dj&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4';

    /**
     * Sports Xml Url
     *
     * @var string
     */
    protected $sportsXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSports?email=offerscenter@gmail.com&password=dflkjh3497dj&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4';

    /**
     * Leagues Xml Url
     *
     * @var string
     */
    protected $leaguesXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetLeagues?email=offerscenter@gmail.com&password=dflkjh3497dj&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4';

    /**
     * Events Xml Url
     *
     * @var string
     */
    protected $eventsXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSportEvents?email=offerscenter@gmail.com&password=dflkjh3497dj&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4&offertypes=52,1,6,7,2,220,221,17,4,22,23,71,42,41,25,16,13,45,21,32,22,62,5&bookmakers=13';

    /**
     * Events by id Xml Url
     *
     * @var string
     */
    protected $eventsXmlById = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSportEventByID?email=offerscenter@gmail.com&password=dflkjh3497dj&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4&eventID=';

    /**
     * Feed Update Mode Status
     */
    const FEED_UPDATE_STATUS = true;

    /**
     * CakeEventManager
     *
     * @var CakeEventManager $CakeEventManager null
     */
    protected $CakeEventManager = null;

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Feeds.OddService', 'Country', 'Sport', 'League', 'Event', 'Bet', 'BetPart', 'Setting', 'Currency', 'Ticket');

    /**
     * Returns self instance
     *
     * @var OddServiceShell|null $_instance
     */
    private static $_instance = null;

    /**
     * Constructor
     *
     *  * Attaches Event Listener
     */
    public function __construct() {
        $this->CakeEventManager = new CakeEventManager();
        $this->CakeEventManager->instance()->attach(new SettingsListener());
        $this->CakeEventManager->instance()->attach(new OddsServiceListener());
        $this->CakeEventManager->instance()->dispatch(new CakeEvent('Controller.App.beforeFilter', $this, array()));

        parent::__construct();
    }

    /**
     * Initializes the Shell
     */
    public function initialize() {
        $this->TimeZone = new TimeZoneComponent(new ComponentCollection());
        $this->TimeZone->initialize(new Controller());
    }

    /**
     * Returns this instance
     *
     * @return EnetPulseShell|null
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Main
     */
    public function main()
    {
        $this->out('It works!');
    }

    /**
     * Imports | Updates ALl
     */
    public function importAll()
    {
        $this->importCountries();
        $this->importSports();
        $this->importLeagues();
        $this->importEvents();
    }

    /**
     * Imports | Updates Countries
     */
    public function importCountries()
    {
        /** @var SimpleXMLElement $xml */
        $CountriesXml = $this->OddService->download(self::FEED_PROVIDER, 'Countries', $this->countriesXml, self::FEED_UPDATE_STATUS);

        foreach($CountriesXml->Results->Country AS $Country)
        {
            $CountryImportId    =   (string) $Country->attributes()->id;
            $CountryName        =   (string) $Country->attributes()->name;

            $SportId            =   $this->Country->insertCountry($CountryImportId, $CountryName);

            $this->out('#Country:' . $SportId . '-' . $CountryImportId);
        }
    }

    /**
     * Imports | Updates Sports
     */
    public function importSports()
    {
        /** @var SimpleXMLElement $xml */
        $SportsXml = $this->OddService->download(self::FEED_PROVIDER, 'Sports', $this->sportsXml, self::FEED_UPDATE_STATUS);

        foreach($SportsXml->Results->Sport AS $Sport)
        {
            $SportImportId  = (string) $Sport->attributes()->id;
            $SportName      = (string) $Sport->attributes()->name;

            $SportId        = $this->Sport->insertSport($SportImportId, $SportName, self::FEED_PROVIDER);

            $this->out('#' . $SportId . '-' . $SportImportId. ' - ' . (string) $Sport->attributes()->name);
        }
    }

    /**
     * Imports | Updates Leagues
     */
    public function importLeagues()
    {
        /** @var SimpleXMLElement $xml */
        $xml = $this->OddService->download('OddService', 'Leagues', $this->leaguesXml, self::FEED_UPDATE_STATUS);

        foreach($xml->Results->League AS $League)
        {
            $Sport              =   $this->Sport->getSportByImportId((string) $League->attributes()->sportID);
            $SportId            =   !empty($Sport) ? $Sport['Sport']['id'] : 0;

            $LeagueImportId     =   (string) $League->attributes()->id;
            $LeagueName         =   (string) $League->attributes()->name;
            $CountryImportId    =   (string)   $League->attributes()->locationID;

            $CountryData        =   $this->Country->getCountryByImportId($CountryImportId);

            if(!is_array($CountryData) || empty($CountryData)) { continue; }

            $CountryId          =   (string) $CountryData['Country']['id'];

            $LeagueId       = $this->League->insertLeague($LeagueImportId, $SportId, $CountryId, $LeagueName, self::FEED_PROVIDER);

            $this->out('#League:' . $LeagueId . '-' . $LeagueImportId . ' #Sport: '  . $SportId . ' - ' . $LeagueName);
        }
    }

    /**
     * ReImports | Updates Non Resulting Events
     */
    public function updateResults1Day() {
        $limit  = 50;
        $i      = 0;
        $count  = (string) ceil($this->Event->getNonResultingEvents1Day('count') / $limit);

        while($count > $i) {

            $Events = implode(',', array_map(function($Event) {
                return $Event['Event']['import_id'];
            }, $this->Event->getNonResultingEvents1Day('all', $i * $limit, $limit)));

            $this->importEvents('Results', $this->eventsXmlById . $Events, self::FEED_UPDATE_STATUS, false, true);

            $i++;
        }
    }

    /**
     * ReImports | Updates Non Resulting Events
     */
    public function updateResults30Min() {
        $limit  = 50;
        $i      = 0;

        $count  = (string) ceil($this->Event->getNonResultingEvents30Min('count') / $limit);

        while($count > $i) {

            $Events = implode(',', array_map(function($Event) {
                return $Event['Event']['import_id'];
            }, $this->Event->getNonResultingEvents30Min('all', $i * $limit, $limit)));

            $this->importEvents('Results', $this->eventsXmlById . $Events, self::FEED_UPDATE_STATUS , false, true);

            $i++;
        }
    }

    /**
     * Update Events By Id
     */
    public function updateEvents()
    {
        $this->importEvents('updateEvents', $this->eventsXmlById . implode(',', $this->args), self::FEED_UPDATE_STATUS , false, true);
    }

    /**
     *
     *
     * @param string $type
     * @param null   $url
     * @param null   $update
     * @param bool   $updateByTimestamp
     * @param bool   $events
     *
     * @return mixed|void
     */
    public function importEvents($type = 'Events', $url = null, $update = null, $updateByTimestamp = true, $events = false)
    {
        if($url == null) { $url = $this->eventsXml; }
        if(is_null($update)) { $update = self::FEED_UPDATE_STATUS; }

        /** @var SimpleXMLElement $xml */
        $xml = $this->OddService->download('OddService', $type, $url, $update, $updateByTimestamp);

        $this->out('Total events to process: ' . (string)  count( $xml->Results->children() ) );

        try {
            foreach($xml->Results->Event AS $Event)
            {
                try {
                    $League         =   $this->League->getLeagueByImportId( (string) $Event->LeagueID );

                    if(empty($League)) {
                        $this->out('League not found, skipping: ' . (string) $Event->LeagueID);
                        continue;
                    }

                    $LeagueId       =   $League['League']['id'];

                    $ImportEventId  =   (string)    $Event->EventID;
                    $EventName      =   (string) $Event->HomeTeam->attributes()->Name . ' - ' . (string) $Event->AwayTeam->attributes()->Name;
                    $EventStartDate =   $this->TimeZone->convertTimeZone( date( 'Y-m-d H:i:s', strtotime ((string) $Event->StartDate) ) , OddService::FEED_TIME_ZONE, 'Europe/London' );

                    $OddServiceXml   =   new OddServiceXml();

                    /** @var OddServiceXmlPreMatch $PreMatch */
                    $PreMatch = $OddServiceXml->getFeedTypeInstance('PreMatch');

                    /** @var  OddServiceXmlPreMatchParser_Event $EventObj */
                    $EventObj = $PreMatch::loadObjectClass('Event');

                    $EventObj->setObject($Event);

                    $EventStatus = $EventObj->getStatus();

                    $EventType  =  $EventObj->getType();

                    $ImportedEvent  =   $this->Event->getEventByImportId($ImportEventId, self::FEED_PROVIDER);

                    if(!empty($ImportedEvent)) {
                        $ImportedEvent['Bet'] = $this->Bet->getBets($ImportedEvent['Event']['id']);
                    }

                    $this->out('Imported EventId: ' . $ImportEventId);

                    $EventId = !empty($ImportedEvent['Event']['id']) ? $ImportedEvent['Event']['id'] : null;

                    $EventId    =   $this->Event->saveEvent($EventId, $ImportEventId, $EventName, $EventStatus, $EventType, null, $EventStartDate, date('Y-m-d H:i:s'), $eventResult = '', $eventState = Event::EVENT_ACTIVE_STATE, $LeagueId, self::FEED_PROVIDER);

                    if(!$this->OddService->hasValidOutcomes($Event->Outcomes)) {
                        $this->out('Event: ' . $ImportEventId . ' has not valid outcomes');
                        continue;
                    }

                    if(!$this->OddService->hasOutcomeValidOdds($Event->Outcomes, empty($ImportedEvent))) {
                        $this->out('Event: ' . $ImportEventId . ' has not valid odds');
                        if($events == false) {
                            $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                        }
                        continue;
                    }

                    $this->CakeEventManager->instance()->dispatch(new CakeEvent('Shell.OddService.ImportEvent.insertEvent', $this, array(
                        'EventId'           =>  $EventId,
                        'Event'             =>  $Event,
                        'updateResults'     =>  $events
                    )));

                    $Outcomes   =   $this->OddService->getEventValidOutcomes($Event->Outcomes);

                    foreach($Outcomes->Outcome AS $Outcome)
                    {
                        $BetImportId    =   (string) ($Outcome->attributes()->id . $ImportEventId);
                        $BetName        =   (string) $Outcome->attributes()->name;
                        $BetLive        =   0;
                        $importedBet    =   $this->OddService->getImportedBet($ImportedEvent, $BetImportId);

                        $BetId          =   $this->Bet->insertBet($BetImportId, $EventId, $BetName, $BetName, $importedBet);


                        $Odds           =   $this->OddService->getOutcomeValidOdds($BetName, $Outcome->Bookmaker, $importedBet);

                        if( count( $Odds->children() )  == 0 ) {
                            $EventBets = $this->Bet->getBets($EventId);
                            if (empty($EventBets)) {
                                $this->Event->updateState($EventId, Event::EVENT_INACTIVE_STATE);
                            }
                            $this->out('#Skip Outcomes: ($Odds->count() == 0) ImportId ' . $BetName . ' ' . $ImportEventId);
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

                        foreach($Odds->xpath('Odds') AS $Odd)
                        {
                            $betPartImportId    =   (string) preg_replace("/[^0-9]/","",$Odd->attributes()->importId);
                            $betPartName        =   (string) $Odd->attributes()->name;
                            $betPartOdd         =   (string) $Odd->attributes()->currentPrice;
                            $line               =   (string) $Odd->attributes()->line;
                            $isWinning          =            $Odd->attributes()->isWinner;

                            $BetPartId          =   $this->BetPart->insertBetPart($betPartImportId, $betPartName, $BetId, $betPartOdd, $line, $importedBet);

                            if($events == true) {
                                $this->CakeEventManager->instance()->dispatch(new CakeEvent('Shell.OddService.ImportEvent.insertBetPart', $this, array(
                                    'BetPartId' =>  $BetPartId,
                                    'isWinner'  =>  $isWinning
                                )));
                            }
                        }
                    }
                } catch (Exception $e) {
                    $this->out($e->getMessage());
                }

            }
        } catch (Exception $e) {
            $this->out($e->getMessage());
        }

    }

    public function importLiveEvents()
    {
        /** @var SimpleXMLElement $xml */
        $xml = $this->OddService->download('OddService', 'InPlay', 'http://www.google.com/url?q=http%3A%2F%2Fxml.oddservice.com%2FOS%2FOddsWebService.svc%2FGetLiveSportEvents%3Femail%3Dofferscenter%2540gmail.com%26password%3Ddflkjh3497dj%26guid%3D8bafec66-1d8c-410b-a786-2aaa9820d4be%26lang%3Den%26&sa=D&sntz=1&usg=AFQjCNHeJTkyaUsdmrXcXpZPN3AMQAN7UA', null, false);

        /** @var OddServiceXml $OddServiceXml */
        $OddServiceXml   =   new OddServiceXml();

        /** @var OddServiceXmlInPlay $InPlay */
        $InPlay = $OddServiceXml->getFeedTypeInstance('InPlay');

        /** @var OddServiceXmlInPlayParser_Event $EventObj */
        $EventObj = $InPlay::loadObjectClass('Event');

        try {
            foreach ($xml->Results->Event AS $Event) {
                try {
                    $EventObj->setObject($Event);
                    $EventStatus = $EventObj->getStatus();
                    $EventType  =  $EventObj->getType();
                } catch (Exception $e) {
                    $this->out($e->getMessage());
                }
            }
        } catch (Exception $e) {
            $this->out($e->getMessage());
        }
    }

    public function parseXml()
    {
        $this->out(__("Parsing xml start!"));

        $limit      =   50;
        $i          =   0;

        $conditions = array(
            'OddService.state'   =>  1,
//            'OddService.id'   =>  275
        );

        $queryCount = $this->OddService->find('count', array(
            'conditions'    =>  $conditions
        ));

        $count  = (string) ceil($queryCount / $limit);

        $this->out(__("Total objects count: %d", $queryCount));
        $this->out(__("Total queries count: %d", $count));

        $OddServiceXml   =   new OddServiceXml();
        $Directory      =   $this->OddService->getXmlDirectory();

        while($count > $i) {
            $this->out(__("i: %d; Offset: %d; Limit: %d", $i, $i * $limit, $limit));
            $xmlList        =   $this->OddService->find('all', array(
                'conditions'    =>  $conditions,
                'limit'         =>  $limit,
                'offset'        =>  $i * $limit,
                'order'         =>  array('OddService.id ASC')
            ));

            foreach ($xmlList AS $index => $xml) {
                try {
                    $xmlFile = $Directory . $xml[$this->name]["id"] . ".xml";

                    if (!is_readable($xmlFile)) {
                        throw new Exception(sprintf("Can't find xml file! ( %d )", $xml[$this->name]["id"]), 500);
                    }

                    $this->out(__("Parsing xml by id: %d", $xml[$this->name]["id"]));

                    $OddServiceXml->processFeed(file_get_contents($xmlFile));

                    // $this->OddService->setState($xml[$this->name]["id"], 2); // TODO: revert

                } catch (Exception $e) {
                    $this->out($e->getMessage());
                    $this->OddService->setState($xml[$this->name]["id"], 3);
                    CakeLog::critical($e->getMessage(), $this->name);
                }

                unset($xmlList[$index]);
            }

            $i++;
        }
    }
}