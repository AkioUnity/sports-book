<?php

App::uses('FeedsAppModel', 'Feeds.Model');
App::uses('Ticket', 'Model');
App::uses('Sport', 'Model');
App::import('Utility', 'Xml');

class Betclick extends FeedsAppModel
{
    public $name = 'Betclick';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'betclick_xml';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'betclick_xml';

    private $live = false; // premat

    protected $validOutcomes = array(
        0   =>  'match winner', // 12
        1   =>  'match result', // 1X2
        2   =>  'double chance', // Double Chance
        3   =>  'over/under', //
        4   =>  'correct score',
        5   =>  'first team to score',
        6   =>  'european handicap',
        7   =>  'total goals', // 1x2 what ever
        8   =>  'half-time result', // 1x2
        9   =>  'number of goals', // correct score
        10  =>  'half-time correct score', // correct score
        11  =>  'half-time / full-time',  // correct score clean
        12  =>  'total sets', // 1x2 what ever
        13  =>  'total games', // under over
        14  =>  'total points', // under over
        15  =>  'handicap', // handicap
        16  =>  'handicap result', // handicap
        17  =>  'outright winner', // correct score
        18  =>  'winner', // correct score
        20  =>  'second round 2 ball', // 1x2 what ever
        22  =>  'first set total points', // under over whatever
        23  =>  'sets handicap', // 12 whatever
        24  =>  'place 1-3', //
        25  =>  'first period result', //
        26  =>  'first period total goals',
        27  =>  'time of first goal', //
        28  =>  'first period double chance',
        29  =>  'drivers winner',
        30  =>  'champion',
        31  =>  'championship winner',
        32  =>  'drivers championship winner',
        33  =>  'constructors championship',
        34  =>  'head-to-head championship',
        35  =>  'relegation',
        36  =>  'place 1-4',
        37  =>  'place 1-2',
        38  =>  'race winner',
        39  =>  'podium',
        40  =>  'stage winner',
        41  =>  'goalscorer',
        42  =>  'last goalscorer',
        43  =>  'first goalscorer',
        44  =>  'under/over', //
        45  =>  'away team over/under',
        46  =>  'home team over/under',
        47  =>  'match and goals',
        48  =>  'clean sheet',
        49  =>  'match result/both teams to score',
        50  =>  'double chance & both teams to score',
        51  =>  'away team to win to nil',
        52  =>  'draw no bet',
        53  =>  'away team total goals odd/even',
        54  =>  'home team total goals odd/even',
        55  =>  'both teams to score',
        56  =>  'home team total goals',
        57  =>  'away team total goals',
        58  =>  'total goals odd/even',
        59  =>  'away team clean sheet',
        60  =>  'next goal in regular time',
        61  =>  'half with most goals',
        62  =>  'first half result/both teams to score',
        63  =>  'home team 1st half clean sheet'
    );


    public $skipOutcomes = array(
        'match winner 2'
    );

    protected $validBookmakers = array(
        'Status'    =>  array( 'Open', 'Settled', 'Started', 'Suspended' )
    );

    /**
     * Servers list from which allowed
     * Accept requests
     *
     * @var array
     */
    protected $servers = array(
        0   =>  "95.211.242.30",
        1   =>  "95.211.242.50"
    );

    /**
     * Event Status Finished
     */
    const EVENT_STATUS_FINISHED     =   'finished';

    /**
     * Event Status Cancelled
     */
    const EVENT_STATUS_CANCELLED    =   'cancelled';

    /**
     * Event Status Postponed
     */
    const EVENT_STATUS_POSTPONED    =   'postponed';

    /**
     * Ticket type won mapped value
     */
    const ODD_TYPE_WON          =   1;

    /**
     * Odd type cancelled mapped value
     */
    const ODD_TYPE_CANCELLED    =   2;

    /**
     * Odd type lost mapped value
     */
    const ODD_TYPE_LOST         =   0;

    /**
     * Feed time zone
     */
    const FEED_TIME_ZONE        =   'Europe/London';

    public function setLive($bool)
    {
        $this->live = $bool;
        return $this;
    }

    /**
     * Returns is feed parser job active
     *
     * @param string $parseXmlCommand - job command
     *
     * @return bool
     */
    public function parseFeedTaskInProgress($parseXmlCommand)
    {
        // Ant daugiau core'ų galima naudoti Queue::inProgress(), - pagal load'ą
        foreach (Queue::inStatus() AS $QueueInProgress) {
            if (!empty($QueueInProgress['QueueTask']) && $QueueInProgress['QueueTask']['command'] == $parseXmlCommand && $QueueInProgress['QueueTask']['status'] != 3) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $stream
     *
     * @return string
     */
    public function getXmlFromStream(array $stream)
    {
        return utf8_encode(str_replace("xml_version", "xml version", $stream["data"]));
    }

    /**
     * Validation rule for incoming requests
     *
     * @param $file
     *
     * @return bool
     */
    public function detectServer($file)
    {
        if (!isset($_SERVER['REMOTE_ADDR']) || !isset($_SERVER['SERVER_NAME'])) {
            return false;
        }

        if (defined('DATABASE_CONFIG::DEV_VERSION')) {
            return true;
        }

        return in_array($_SERVER['REMOTE_ADDR'], $this->servers);
    }

    /**
     * Used when validating a file upload in CakePHP
     *
     * @param array $file - Passed from $validate to this function containing our filename
     *
     * @return bool - True or False is passed or failed validation
     * @throws Exception
     */
    public function receiveFile($file)
    {
        if (empty($file)) {
            throw new Exception(__("%s feed xml empty request has passed.", $this->name), 500);
        }

        if (count($file) != 1) {
            throw new Exception(__("%s feed xml expected %d params count, got %d", $this->name, 1, count($file)), 500);
        }

        try {
            return Xml::build($this->getXmlFromStream($file));
        } catch (Exception $e) {
            throw new Exception(__("%s feed xml validate error: %s", $this->name, $e->getMessage()));
        }
    }


    /**
     * Returns xml files working directory path
     *
     * @return string
     */
    public function getXmlDirectory()
    {
        return APP . 'tmp' . DS . 'xml' . DS . $this->name . DS;
    }


    /**
     * Updates xml state
     *
     * @param int $id    - xml id
     * @param int $state - xml state
     *
     * @return void
     */
    public function setState($id, $state)
    {
        $this->clear();

        $this->id = $id;

        $this->save(array('state' => $state, 'updated' => time()), false);
    }

    /**
     * Is ping?
     *
     * @return bool
     */
    public function ping()
    {
        return strpos($this->getXmlFromStream($_POST), '<KeepAliveMessage>') !== false;
    }

    /**
     * Saves xml file from provider push service
     *
     * @return void
     *
     * @throws Exception
     */
    public function saveFile()
    {
        $xml        =   $this->save(array('created' =>  time(), 'updated' => time(), 'state' => 0));
        $directory  =   $this->getXmlDirectory();

        if (!$xml) {
            throw new Exception(__("%s feed cannot save xml entry to database", $this->name), 500);
        }

        if (!file_exists($directory)) {
            mkdir( $directory, 0777, true );
        }

        $this->id = $xml[$this->name]["id"];

        try {
            $xmlFile    =   $directory . $xml[$this->name]["id"] . ".xml";
            $out_file   =   fopen($xmlFile, 'wb');

            fwrite($out_file, $this->getXmlFromStream($_POST));

            // Files are done, close files
            fclose($out_file);

            $this->setState($xml[$this->name]["id"], 1);
        }
        catch (Exception $e) {

            $this->setState($xml[$this->name]["id"], 3);

            throw new Exception($e->getMessage(), 500);
        }
    }

    /**
     * Maps Feed Provided Odds Type With Systems
     *
     * @param $oddType
     * @return int
     */
    public function getOddStatus($oddType)
    {
        if(!is_numeric($oddType)) {
            return Ticket::TICKET_STATUS_PENDING;
        }

        switch($oddType) {
            case self::ODD_TYPE_WON:
                return Ticket::TICKET_STATUS_WON;
                break;
            case self::ODD_TYPE_CANCELLED:
                return Ticket::TICKET_STATUS_CANCELLED;
                break;
            case (string) self::ODD_TYPE_LOST:
                return Ticket::TICKET_STATUS_LOST;
                break;
            default;
                return Ticket::TICKET_STATUS_PENDING;
                break;
        }
    }

    /**
     * Returns imported bet data by import_id
     *
     * @param array $ImportedEvent
     * @param $BetImportId
     * @return array
     */
    public function getImportedBet($ImportedEvent = array(), $BetImportId)
    {
        if( !isset($ImportedEvent['Bet']) ) { return array(); }

        if(!is_array($ImportedEvent['Bet'])) { return array(); }

        if( empty($ImportedEvent['Bet']) ) { return array(); }

        foreach($ImportedEvent['Bet'] AS $Bet) {
           if(!isset($Bet['Bet']['import_id'])) { continue; }
           if($Bet['Bet']['import_id'] == $BetImportId) {
                return $Bet;
           }
        }

        return array();
    }

    /**
     * Check has event any valid outcome
     *
     * @param SimpleXMLElement $Outcomes
     * @return bool
     */
    public function hasValidOutcomes(SimpleXMLElement $Outcomes)
    {
        if(!is_object($Outcomes)) { return false; }

        $hasAnyOutcome = get_object_vars($Outcomes);

        if(empty($hasAnyOutcome)) { return false; }

        return true;
        foreach ($Outcomes->xpath('bet') AS $bet) {
            $name = trim(!$this->live ?  strtolower((string) $bet->attributes()->name) : strtolower((string) $bet->attributes()->bet_name));
            if(in_array($name, $this->validOutcomes)) {
                return true;
            }

        }

        return false;
    }

    /**
     * Returns valid odds by betting type
     *
     * @param $SportId
     * @param $BetType
     * @param SimpleXMLElement $Bookmaker
     * @param $importedBet
     * @param null $tmp
     * @return SimpleXMLElement
     */
    public function getOutcomeValidOdds($EventName, $SportId, $BetType, SimpleXMLElement $Bookmaker, $importedBet, $tmp = null)
    {
        $Bookmaker = $this->assignTeam($EventName, $Bookmaker);

        $Method = $this->getBetType($Bookmaker, $importedBet);

        return $this->{$Method}($Bookmaker, $importedBet);
    }

    public function assignTeam($EventName, SimpleXMLElement $Bookmaker)
    {
        $EventName = explode(" - ", $EventName);

        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            $OddAttributes = $Odd->attributes();
            $OddName = (string) $Odd->attributes()->name;
            $line = null;

            if (strpos($OddName, '%1%') !== false) {
                $OddName = str_replace('%1%', trim($EventName[0]), $OddName);
                $line = 1;
            }
            if (strpos($OddName, '%2%') !== false) {
                $OddName = str_replace('%2%', trim($EventName[1]), $OddName);
                $line = 2;
            }

            if (strpos(strtolower($OddName), 'draw') !== false) {
                $line = 'X';
            }

            $Odd->attributes()->name = $OddName;

            if (!isset($OddAttributes["line"])) {
                $Odd->addAttribute('line', $line);
            }
        }

        return $Bookmaker;
    }

    public function getBetType(SimpleXMLElement $Bookmaker)
    {
        if (count ( $Bookmaker->xpath('choice') ) == 0) {
            return 'Odds_empty';
        }

        if (count ( $Bookmaker->xpath('choice') ) == 2) {
            return 'Odds_12NoName';
        }

        if (count ( $Bookmaker->xpath('choice') ) == 3) {
            return 'Odds_1X2NoName';
        }

        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            if (strpos(((string) $Odd->attributes()->name), 'Under ') !== false) {
                return 'Odds_UnderOver';
            }

            if (strpos(((string) $Odd->attributes()->name), 'Over ') !== false) {
                return 'Odds_UnderOver';
            }
        }

        return 'Odds_CorrectScore';
    }

    public function Odds_empty(SimpleXMLElement $Bookmaker)
    {
        return $Bookmaker;
    }

    public function Odds_clean(SimpleXMLElement $Bookmaker)
    {
        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            $Odd->attributes()->name = preg_replace("/[^0-9A-zA-Z ()+-]/", "", (string) $Odd->attributes()->name);
        }

        return $Bookmaker;
    }

    /**
     * 12 Odds Type Integration
     *
     * @param SimpleXMLElement $Bookmaker
     * @param array $importedBet
     * @return SimpleXMLElement
     */
    public function Odds_12NoName(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   2;
        $maxOddsCount   =   2;

        if( (int) count ( $Bookmaker->xpath('choice') ) > $maxOddsCount) {
            unset($Bookmaker->choice);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) < $minOddsCount) {
            unset($Bookmaker->choice);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    /**
     * 12 Odds Type Integration
     *
     * @param SimpleXMLElement $Bookmaker
     * @param array $importedBet
     * @return SimpleXMLElement
     */
    public function Odds_12(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   2;
        $maxOddsCount   =   2;

        $namesMap       =   array(1, 2);

        foreach ($Bookmaker->xpath('choice') AS $Odd) {
            $Odd->attributes()->name = preg_replace("/[^0-9,.]/", "", (string) $Odd->attributes()->name);

            if(!in_array( (string) $Odd->attributes()->name, $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) > $maxOddsCount) {
            unset($Bookmaker->choice);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) < $minOddsCount) {
            unset($Bookmaker->choice);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    /**
     * 1X2 Odds Type Integration
     *
     * @param SimpleXMLElement $Bookmaker
     * @param array $importedBet
     * @return SimpleXMLElement
     */
    public function Odds_1X2(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   3;
        $maxOddsCount   =   3;

        $namesMap       =   array('%1%', 'draw', '%2%');

        if (count ( $Bookmaker->xpath('choice') ) == 2) {
            return $this->Odds_12($Bookmaker, $importedBet); // In case of handicap..
        }

        foreach ($Bookmaker->xpath('choice') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->name ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            } else {
                $Odd->attributes()->name = preg_replace("/[^0-9,.]/", "", (string) $Odd->attributes()->name);

                if ((string) $Odd->attributes()->name == "") {
                    $Odd->attributes()->name = "X";
                }
            }
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) > $maxOddsCount) {
            unset($Bookmaker->choice);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) < $minOddsCount) {
            unset($Bookmaker->choice);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_1X2NoName(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   3;
        $maxOddsCount   =   3;

        if (count ( $Bookmaker->xpath('choice') ) == 2) {
            return $this->Odds_12NoName($Bookmaker, $importedBet); // In case of handicap..
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) > $maxOddsCount) {
            unset($Bookmaker->choice);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) < $minOddsCount) {
            unset($Bookmaker->choice);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('choice') AS $index => $Odd)
        {
            $OddAttributes = $Odd->attributes();
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);


            if ($index == 0) {
                $Odd->attributes()->line = 1;
            }

            if ($index == 1) {
                $Odd->attributes()->line = 'X';
            }

            if ($index == 2) {
                $Odd->attributes()->line = 2;
            }
        }

        return $Bookmaker;
    }

    public function Odds_FirstTeamToScore(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   3;
        $maxOddsCount   =   3;

        $namesMap       =   array(1 => '%1%', 'No Goal' => 'no goal', 2 =>'%2%');

        foreach ($Bookmaker->xpath('choice') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->name ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }else{
                $Odd->attributes()->name = array_search(strtolower((string) $Odd->attributes()->name), $namesMap);
            }
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) > $maxOddsCount) {
            unset($Bookmaker->choice);
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) < $minOddsCount) {
            unset($Bookmaker->choice);
        }

        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    /**
     * Double Chance Odds Type Integration
     * @param SimpleXMLElement $Bookmaker
     * @param array $importedBet
     * @return SimpleXMLElement
     */
    public function Odds_DoubleChance(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   3;
        $maxOddsCount   =   3;

        $namesMap       =   array('12' => '%1% or %2%', '1x' => '%1% or draw', 'x2' => 'draw or %2%');

        foreach ($Bookmaker->xpath('choice') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->name ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }else{
                $Odd->attributes()->name = array_search(strtolower((string) $Odd->attributes()->name), $namesMap);
            }
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) > $maxOddsCount) {
            unset($Bookmaker->choice);
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) < $minOddsCount) {
            unset($Bookmaker->choice);
        }

        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    /**
     * Under / Over Odds Type Integration
     *
     * @param SimpleXMLElement $Bookmaker
     * @param array $importedBet
     * @return SimpleXMLElement
     */
    public function Odds_UnderOver(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $oddsCount      =   2;
        $importedOdds   =   isset($importedBet['BetPart']) ? $importedBet['BetPart'] : array();

        $typesMap       =   array('over', 'under');
        $lines          =   array();

        $maxOddsCount   =   2;
        $minOddsCount   =   2;
//var_dump($Bookmaker); exit;
        /*foreach ($Bookmaker->xpath('choice') AS $Odd) {
            $OddAttributes = $Odd->attributes();
            $name = preg_replace("/[^A-Z-a-z]/", "", (string) $Odd->attributes()->name);
            if(!in_array( strtolower ( $name ), $typesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }else{
                $line = preg_replace("/[^0-9,.]/", "", (string) $Odd->attributes()->name);
                $Odd->attributes()->name = preg_replace("/[^A-Z-a-z]/", "", (string) $Odd->attributes()->name);

                if (!isset($OddAttributes["line"])) {
                    $Odd->addAttribute('line', $line);
                }else{
                    $Odd->attributes()->line = $line;
                }

                $lines[md5($line)]   = $line;
            }
        }

        if(empty($importedOdds) && !isset($importedOdds[0]['line'])) {
            $line = array_search("2.5", $lines);
            $line = $line === false ? @$lines[ array_rand($lines) ] : $lines[$line];
        }else{
            $line = $importedOdds[0]['line'];
        }

        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            if( (string) $Odd->attributes()->line != $line ) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) > $maxOddsCount) {
            unset($Bookmaker->choice);
        }

        if( (int) count ( $Bookmaker->xpath('choice') ) < $minOddsCount) {
            unset($Bookmaker->choice);
        }*/

        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }


    /**
     * Correct Score Odds Type Integration
     *
     * @param SimpleXMLElement $Bookmaker
     * @param array $importedBet
     * @return SimpleXMLElement
     */
    public function Odds_CorrectScore(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        foreach($Bookmaker->xpath('choice') AS $Odd)
        {
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    /**
     * Returns Valid Event Outcomes
     *
     * @param SimpleXMLElement $Outcomes
     * @return SimpleXMLElement
     */
    public function getEventValidOutcomes(SimpleXMLElement $Outcomes)
    {
        foreach($Outcomes->xpath('bet') AS $Outcome) {
            $name = !$this->live ? strtolower((string) $Outcome->attributes()->name) : strtolower((string) $Outcome->attributes()->bet_name);
            if(in_array(trim($name), $this->skipOutcomes)) {
                $DOM = dom_import_simplexml($Outcome);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        return $Outcomes;
    }

    /**
     * Downloads and returns xml
     *
     * @param $name
     * @param $type
     * @param $url
     * @param bool $update
     * @param bool $updateByTimestamp
     * @return DOMDocument|SimpleXMLElement
     */
    public function download($name, $type, $url, $update = false, $updateByTimestamp = false)
    {
        $directory = APP . 'tmp' . DS . 'xml' . DS . $name . DS;

        $timestamp = null;

        if(!$update && file_exists($directory . Inflector::slug($type) . '.xml') )
        {
            if(file_get_contents($directory . Inflector::slug($type) . '.xml') != "")
            {
                try {
                    $xml =  Xml::build($directory . Inflector::slug($type) . '.xml');

                    if($update == false) { return $xml; }

                } catch(Exception $e) {

                }
            }
        }
        else{
            if(!file_exists($directory)) {
                mkdir( $directory, 0777, true );
            }
        }

        $data = $this->curl($url);

        file_put_contents($directory . Inflector::slug($type) . '.xml', $data);

        return Xml::build($data);
    }

    public function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }
}