<?php
App::uses('Controller', 'Controller');
App::uses('Model', 'Model');
App::uses('View', 'View');

App::import('Xml');
App::uses('CakeEvent', 'Event');
App::uses('CakeEventManager', 'Event');
App::uses('OddsServiceListener', 'Feeds.Event');

class OddServiceShellTest extends CakeTestCase
{
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
    protected $fullXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSportEvents?email=offerscenter@gmail.com&password=Newoddservice!&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4&bookmakers=7';

    /**
     * Countries Xml Url
     *
     * @var string
     */
    protected $countriesXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetCountries?email=offerscenter@gmail.com&password=Newoddservice!&guid=8bafec66-1d8c-410b-a786-2aaa9820d4be';

    /**
     * Sports Xml Url
     *
     * @var string
     */
    protected $sportsXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSports?email=offerscenter@gmail.com&password=Newoddservice!&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4';

    /**
     * Leagues Xml Url
     *
     * @var string
     */
    protected $leaguesXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetLeagues?email=offerscenter@gmail.com&password=Newoddservice!&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4';

    /**
     * Events Xml Url
     *
     * @var string
     */
    protected $eventsXml = 'http://xml.oddservice.com/OS/OddsWebService.svc/GetSportEvents?email=offerscenter@gmail.com&password=Newoddservice%21&guid=b6804142-fc1c-4087-b9a7-fdec37af96c4&offertypes=52,1,6,7,2,220,221&bookmakers=75';

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


    public $uses = array('OddService');

    /**
     * Setup the test case, backup the static object values so they can be restored.
     */
    public function setUp() {
        parent::setUp();
    }

    public function testImportEvents() {
        /** @var SimpleXMLElement $xml */
        //$xml = $this->OddService->download('OddService', 'Events', $this->eventsXml, self::FEED_UPDATE_STATUS);

        $this->assertEqual(true, false);
    }

    /**
     * Force results update test
     */
    public function testUpdateResults() { }
}