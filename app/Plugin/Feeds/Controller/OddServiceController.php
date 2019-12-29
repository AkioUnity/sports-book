<?php

App::import('Xml');
App::uses('CakeEvent', 'Event');
App::uses('OddsServiceListener', 'Feeds.Event');
App::uses('FeedController', 'Feeds.Controller');
App::uses('TimeZoneComponent', 'Controller/Component');

class OddServiceController extends FeedsAppController implements FeedController {

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
    public $fullXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSportEvents?email=offerscenter@gmail.com&password=Newoddservice!&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4&bookmakers=7';

    /**
     * Countries Xml Url
     *
     * @var string
     */
    public $countriesXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetCountries?email=offerscenter@gmail.com&password=Newoddservice!&guid=8bafec66-1d8c-410b-a786-2aaa9820d4be';

    /**
     * Sports Xml Url
     *
     * @var string
     */
    public $sportsXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSports?email=offerscenter@gmail.com&password=Newoddservice!&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4';

    /**
     * Leagues Xml Url
     *
     * @var string
     */
    public $leaguesXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetLeagues?email=offerscenter@gmail.com&password=Newoddservice!&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4';

    /**
     * Events Xml Url
     *
     * @var string
     */
    public $eventsXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSportEvents?email=offerscenter@gmail.com&password=Newoddservice%21&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4&offertypes=52,1,6,7,2,220,221,17,4,22,23,71,42,41,25,16,13,45,21,32,22,62,5&bookmakers=13';

    /**
     * Events by id Xml Url
     *
     * @var string
     */
    public $eventsXmlById = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSportEventByID?email=offerscenter@gmail.com&password=Newoddservice%21&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4&eventID=';

    /**
     * Feed Update Mode Status
     */
    const FEED_UPDATE_STATUS = false;

    public $headerStatus = null;

    public $headerDescription = null;

    public $headerTimeStamp = null;

    public $headerClientsTimestamp = null;



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

    public $xml;

    /**
     * Constructor
     *
     *  * Attaches Event Listener
     */
    public function __construct($request = null, $response = null) {

        $this->CakeEventManager = new CakeEventManager();
        $this->CakeEventManager->instance()->attach(new SettingsListener());
        $this->CakeEventManager->instance()->attach(new OddsServiceListener());

        $this->CakeEventManager->instance()->dispatch(new CakeEvent('Controller.App.beforeFilter', $this, array()));
        $this->TimeZone = new TimeZoneComponent(new ComponentCollection());
        $this->TimeZone->initialize(new Controller());
        parent::__construct($request, $response);

    }

    /**
     * Receive file
     *
     * @return void
     */
    public function receive_file()
    {
        try {
            $this->OddService->set($_POST); // $this->request->data nemato ['data']

            CakeLog::debug(var_export($_REQUEST, true));

            if (!$this->OddService->validates()) {
                throw new Exception(__("%s XML file does not meets validation rules. Import Aborting.", $this->name), 500);
            }

            try {
                if ($this->OddService->ping()) {
                    echo 'XML_PING_OK';
                } else {
                    $this->OddService->saveFile();

                    CakeLog::notice('Xml file successful received and ready to process.', $this->name);

                    $parseXmlCommand = 'Feeds.OddService parseXml';
                    Queue::add($parseXmlCommand, 'shell', array('priority' => 1));
                    if (!$this->OddService->parseFeedTaskInProgress($parseXmlCommand)) {
                        Queue::add($parseXmlCommand, 'shell', array('priority' => 1));
                    }

                    echo 'XML_RECEIVED_OK';
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), 500);
            }
        }
        catch (Exception $e) {

            CakeLog::critical($e->getMessage(), $this->name);

            echo 'XML_RECEIVED_FAILED';
        }
        exit;
    }
}