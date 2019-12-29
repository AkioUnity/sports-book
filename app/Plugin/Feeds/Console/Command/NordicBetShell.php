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

class UpdateNordicBetShell extends FeedAppShell implements FeedShell
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
    const FEED_PROVIDER = 'NordicBet';

    /**
     * Full Xml Url
     *
     * @var string
     */
    protected $fullXml = 'http://xml.nordicbet.com/full.xml';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Feeds.NordicBet', 'Sport', 'League', 'Event', 'Bet', 'BetPart');

    /**
     * Initializes the Shell
     */
    public function initialize() {
        $this->TimeZone = new TimeZoneComponent(new ComponentCollection());
        $this->TimeZone->initialize(new Controller());
    }

    /**
     * Main
     */
    public function main()
    {
        $this->out('It works!');
    }

    /**
     * Imports | Updates Full Feed
     */
    public function importFull()
    {
        /** @var SimpleXMLElement $xml */
        $xml = $this->NordicBet->download(self::FEED_PROVIDER, 'Full', $this->fullXml);

        foreach ($xml->Game as $gameNode)
        {
            $live = (string) $gameNode->LiveBet;

            if ($live == 'True') { continue; }

            $eventImportId  = (string) $gameNode->attributes()->id;
            $eventName      = (string) $gameNode->attributes()->name;

            $sportName = (string) $gameNode->Sport;
            $sportImportId = crc32(strtolower($sportName));

            $sportId = $this->Sport->insertSport($sportImportId, $sportName, self::FEED_PROVIDER);

            $this->out('Sport: '.'#' . $sportId . '-' . $sportImportId . ' - ' . (string) $sportName);


            $leagueName         = (string) $gameNode->Region . ' - ' . (string) $gameNode->Season;
            $leagueImportId     = crc32(strtolower($leagueName));
            $leagueId           = $this->League->insertLeague($leagueImportId, $leagueName, $sportId, self::FEED_PROVIDER);

            $this->out('League: #' . $leagueId . '-' . $sportId . ' - ' . $leagueName);

            if ($eventName == "Winner") {
                $eventName = (string) $gameNode->Season;
            }

            $bettingEndTime = (string) $gameNode->BettingEndTime;
            $date           = strtotime($bettingEndTime);
            $eventDate      = gmdate("Y-m-d H:i:s", $date);

            $eventId = $this->Event->insertEvent($eventImportId, $eventName, $eventDate, $leagueId, self::FEED_PROVIDER);

            $this->out('Event: #' . $eventId . ' - ' . $eventName);

            foreach ($gameNode->OutcomeSet as $outcomeSetNode) {

                $betImportId    = (string) $outcomeSetNode->attributes()->id;
                $betName        = (string) $outcomeSetNode->attributes()->name;
                $betType        = (string) $outcomeSetNode->attributes()->type;

                if (empty($betType)) {
                    $betType = 'Outright';
                } else if ($betType == 'Result') {
                    if (count($outcomeSetNode->Outcome) == 2) {
                        $betType = "Versus";
                    } else {
                        $betType = "Versus (with Draw)";
                    }
                }

                $betId = $this->Bet->insertBet($betImportId, $betName, $eventId, $betType);

                foreach ($outcomeSetNode->Outcome as $outcomeNode) {

                    $betPartImportId    = (string) $outcomeNode->attributes()->id;
                    $betPartOdd         = (string) $outcomeNode->attributes()->odds;
                    $betPartName        = (string) $outcomeNode->attributes()->name;

                    if (isset($outcomeNode->Participant)) {
                        if (($betType == 'Under/Over Match') || ($betType == 'Under/Over Team')) {
                            //$betPartName = (string) $outcomeNode->Participant;
                        } else {
                            $betPartName = (string) $outcomeNode->Participant;
                        }
                    }

                    $this->BetPart->insertBetPart($betPartImportId, $betPartName, $betId, $betPartOdd);
                }
            }
        }
    }

    /**
     * Method which implements countries importing / updating
     *
     * @return mixed
     */
    public function importCountries() {}

    /**
     * Method which implements sports importing / updating
     *
     * @return mixed
     */
    public function importSports() {}

    /**
     * Method which implements leagues importing / updating
     *
     * @return mixed
     */
    public function importLeagues() {}

    /**
     * Method which implements events importing / updating
     *
     * @return mixed
     */
    public function importEvents() {}
}