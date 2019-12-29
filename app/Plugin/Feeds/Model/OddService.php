<?php

App::uses('FeedsAppModel', 'Feeds.Model');
App::uses('Ticket', 'Model');

class OddService extends FeedsAppModel
{
    public $name = 'OddService';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'oddservice_xml';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'oddservice_xml';

    /**
     * List of validation rules. It must be an array with the field name as key and using
     * as value one of the following possibilities
     *
     * @var array
     */
    public $validate = array(
        'data' => array(
            'valid-server'  =>  array(
                'required'  =>  true,
                'rule'      =>  array('detectServer'), // Is a function below
                'message'   =>  'Unknown server'
            ),
            'upload-file'   => array(
                'required'  =>  true,
                'rule'      =>  array('receiveFile'), // Is a function below
                'message'   =>  'Error uploading file'
            )
        )
    );

    protected $validOutcomes = array(
        0   =>  '12',
        1   =>  '1X2',
        2   =>  'Correct Score',
        3   =>  'Under/Over',
        4   =>  'Under/Over - Away',
        5   =>  'Under/Over - Home',
        6   =>  'Double Chance',
        7   =>  'HT/FT',
        8   =>  'Both Teams To Score',
        9   =>  'Away Team to Score',
        10  =>  'Home Team to Score',
        11  =>  'Odd/Even',
        12  =>  'Odd/Even Halftime',
        13  =>  'Away Team to Score',
        14  =>  'Under/Over 1st Period',
        15  =>  'Under/Over 2nd Period',
        16  =>  'European Handicap',
        17  =>  'First Team To Score',
        18  =>  'Double Chance Halftime',
        19  =>  '1st Period Winner',
        20  =>  '2nd Period Winner',
        21  =>  'Highest Scoring Half'
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

        foreach($Outcomes->Outcome AS $Outcome) {

            if(in_array((string) $Outcome->attributes()->name, $this->validOutcomes)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks has Outcomes any valid odd
     *
     * @param SimpleXMLElement $Outcomes
     * @return bool
     */
    public function hasOutcomeValidOdds(SimpleXMLElement $Outcomes)
    {
        if(!$this->hasValidOutcomes($Outcomes)) { return false; }

        foreach($Outcomes->Outcome AS $Outcome)
        {
            foreach($Outcome->Bookmaker->Odds AS $Bookmaker) {
                foreach($this->validBookmakers AS $bookmakerKey => $bookmakerValue) {
                    if(!is_array($bookmakerValue)) {
                        if( ( string ) $Bookmaker->attributes()->$bookmakerKey != null) {
                            return true;
                        }

                        if( ( string ) $Bookmaker->attributes()->$bookmakerKey != $bookmakerValue) {
                            return false;
                        }
                    }else{
                        if(!in_array( ( string ) $Bookmaker->attributes()->$bookmakerKey, $bookmakerValue)) {
                            return false;
                        }else{
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Returns valid odds by betting type
     *
     * @param $BetType
     * @param SimpleXMLElement $Bookmaker
     * @param $importedBet
     * @return SimpleXMLElement
     */
    public function getOutcomeValidOdds($BetType, SimpleXMLElement $Bookmaker, $importedBet)
    {
        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            foreach($this->validBookmakers AS $bookmakerKey => $bookmakerValue) {
                if(!is_array($bookmakerValue)) {
                    if( ( string ) $Odd->attributes()->$bookmakerKey != $bookmakerValue) {
                        $DOM = dom_import_simplexml($Odd);
                        $DOM->parentNode->removeChild($DOM);
                    }
                }else{
                    if(!in_array( ( string ) $Odd->attributes()->$bookmakerKey, $bookmakerValue)) {
                        $DOM = dom_import_simplexml($Odd);
                        $DOM->parentNode->removeChild($DOM);
                    }
                }
            }
        }

        switch($BetType) {
            case '12':
                return $this->Odds_12($Bookmaker, $importedBet);
                break;
            case '1X2':
                return $this->Odds_1X2($Bookmaker, $importedBet);
                break;
            case 'European Handicap':
                return $this->Odds_EuropeanHandicap($Bookmaker, $importedBet);
                break;
            case 'First Team To Score':
                return $this->Odds_FirstTeamToScore($Bookmaker, $importedBet);
                break;
            case 'Correct Score':
                return $this->Odds_CorrectScore($Bookmaker, $importedBet);
                break;
            case 'Under/Over':
                return $this->Odds_UnderOver($Bookmaker, $importedBet);
                break;
            case 'Under/Over 1st Period':
                return $this->Odds_UnderOver1stPeriod($Bookmaker, $importedBet);
                break;
            case 'Under/Over 2nd Period':
                return $this->Odds_UnderOver2stPeriod($Bookmaker, $importedBet);
                break;
            case 'Double Chance':
                return $this->Odds_DoubleChance($Bookmaker, $importedBet);
                break;
            case 'Double Chance Halftime':
                return $this->Odds_DoubleChanceHalftime($Bookmaker, $importedBet);
                break;
            case 'HT/FT':
                return $this->Odds_HT_FT($Bookmaker, $importedBet);
                break;
            case 'Both Teams To Score':
                return $this->Odds_BothTeamsToScore($Bookmaker, $importedBet);
                break;
            case 'Away Team to Score':
                return $this->Odds_AwayTeamToScore($Bookmaker, $importedBet);
                break;
            case 'Home Team to Score':
                return $this->Odds_HomeTeamToScore($Bookmaker, $importedBet);
                break;
            case 'Odd/Even':
                return $this->Odds_OddEven($Bookmaker, $importedBet);
                break;
            case 'Odd/Even Halftime':
                return $this->Odds_OddEvenHalftime($Bookmaker, $importedBet);
                break;
            case '1st Period Winner':
                return $this->Odds_st1PeriodWinner($Bookmaker, $importedBet);
                break;
            case '2nd Period Winner':
                return $this->Odds_nd2PeriodWinner($Bookmaker, $importedBet);
                break;
            case 'Highest Scoring Half':
                return $this->Odds_HighestScoringHalf($Bookmaker, $importedBet);
                break;
        }


        echo 'TODO implement ' . $BetType . ' type';
        exit;
    }

    public function Odds_HomeTeamToScore(SimpleXMLElement $Bookmaker, $importedBet = array()) {
        $oddsCount      =   2;

        $typesMap       =   array('yes', 'no');

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {

            if(!in_array( strtolower( (string) $Odd->attributes()->bet ), $typesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) != $oddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_AwayTeamToScore(SimpleXMLElement $Bookmaker, $importedBet = array()) {
        $oddsCount      =   2;

        $typesMap       =   array('yes', 'no');

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {

            if(!in_array( strtolower( (string) $Odd->attributes()->bet ), $typesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) != $oddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
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
    public function Odds_BothTeamsToScore(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $oddsCount      =   2;

        $typesMap       =   array('yes', 'no');

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {

            if(!in_array( strtolower( (string) $Odd->attributes()->bet ), $typesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) != $oddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
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

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    /**
     * Odd / Event Betting type
     *
     * @param SimpleXMLElement $Bookmaker
     * @param array $importedBet
     * @return SimpleXMLElement
     */
    public function Odds_OddEven(SimpleXMLElement $Bookmaker, $importedBet = array()) {
        $minOddsCount   =   2;
        $maxOddsCount   =   2;

        $namesMap       =   array('even', 'odd');

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_OddEvenHalftime(SimpleXMLElement $Bookmaker, $importedBet = array()) {
        $minOddsCount   =   2;
        $maxOddsCount   =   2;

        $namesMap       =   array('even', 'odd');

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
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

        $namesMap       =   array(1, 'x', 2);

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_HighestScoringHalf(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   3;
        $maxOddsCount   =   3;

        $namesMap       =   array('first half', 'both halves the same', 'second half');

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_st1PeriodWinner(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   3;
        $maxOddsCount   =   3;

        $namesMap       =   array(1, 'x', 2);

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_nd2PeriodWinner(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   3;
        $maxOddsCount   =   3;

        $namesMap       =   array(1, 'x', 2);

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_EuropeanHandicap(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $oddsCount      =   3;
        $importedOdds   =   isset($importedBet['BetPart']) ? $importedBet['BetPart'] : array();

        $typesMap       =   array(1, 'x', 2);
        $lines          =   array();

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {

            if(!in_array( strtolower( (string) $Odd->attributes()->bet ), $typesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }

            $line           = (string) $Odd->attributes()->line;
            $lines[$line]   = !isset($lines[$line]) ? 1 : $lines[$line] + 1;
        }

        if(empty($importedOdds) && !isset($importedOdds[0]['line'])) {
            $line = array_search($oddsCount, $lines);
        }else{
            $line = $importedOdds[0]['line'];
        }

        if(!$line) { unset($Bookmaker->Odds); return $Bookmaker; }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            if( (string) $Odd->attributes()->line != $line ) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_FirstTeamToScore(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   3;
        $maxOddsCount   =   3;

        $namesMap       =   array(1, 'no goal', 2);

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
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
        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
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

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {

            if(!in_array( strtolower( (string) $Odd->attributes()->bet ), $typesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }

            $line           = (string) $Odd->attributes()->line;
            $lines[$line]   = !isset($lines[$line]) ? 1 : $lines[$line] + 1;
        }

        if(empty($importedOdds) && !isset($importedOdds[0]['line'])) {
            $line = array_search($oddsCount, $lines);
        }else{
            $line = $importedOdds[0]['line'];
        }

        if(!$line) { unset($Bookmaker->Odds); return $Bookmaker; }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            if( (string) $Odd->attributes()->line != $line ) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    /**
     *
     * @param SimpleXMLElement $Bookmaker
     * @param array $importedBet
     * @return SimpleXMLElement
     */
    public function Odds_UnderOver1stPeriod(SimpleXMLElement $Bookmaker, $importedBet = array()) {
        $oddsCount      =   2;
        $importedOdds   =   isset($importedBet['BetPart']) ? $importedBet['BetPart'] : array();

        $typesMap       =   array('over', 'under');
        $lines          =   array();

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {

            if(!in_array( strtolower( (string) $Odd->attributes()->bet ), $typesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }

            $line           = (string) $Odd->attributes()->line;
            $lines[$line]   = !isset($lines[$line]) ? 1 : $lines[$line] + 1;
        }

        if(empty($importedOdds) && !isset($importedOdds[0]['line'])) {
            $line = array_search($oddsCount, $lines);
        }else{
            $line = $importedOdds[0]['line'];
        }

        if(!$line) { unset($Bookmaker->Odds); return $Bookmaker; }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            if( (string) $Odd->attributes()->line != $line ) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_UnderOver2stPeriod(SimpleXMLElement $Bookmaker, $importedBet = array()) {
        $oddsCount      =   2;
        $importedOdds   =   isset($importedBet['BetPart']) ? $importedBet['BetPart'] : array();

        $typesMap       =   array('over', 'under');
        $lines          =   array();

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {

            if(!in_array( strtolower( (string) $Odd->attributes()->bet ), $typesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }

            $line           = (string) $Odd->attributes()->line;
            $lines[$line]   = !isset($lines[$line]) ? 1 : $lines[$line] + 1;
        }

        if(empty($importedOdds) && !isset($importedOdds[0]['line'])) {
            $line = array_search($oddsCount, $lines);
        }else{
            $line = $importedOdds[0]['line'];
        }

        if(!$line) { unset($Bookmaker->Odds); return $Bookmaker; }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            if( (string) $Odd->attributes()->line != $line ) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_UnderOverAway() { }

    public function Odds_UnderOverHome() { }

    public function Odds_HT_FT(SimpleXMLElement $Bookmaker, $importedBet = array()) {
        $minOddsCount   =   9;
        $maxOddsCount   =   9;

        $namesMap       =   array('1/1', '2/1', 'x/1', '1/x', '2/x', '1/2', 'x/2', '2/2', 'x/x');

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
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

        $namesMap       =   array('12', '1x', 'x2');

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
            $Odd->attributes()->addAttribute('importId', (string) $Odd->attributes()->id);
        }

        return $Bookmaker;
    }

    public function Odds_DoubleChanceHalftime(SimpleXMLElement $Bookmaker, $importedBet = array())
    {
        $minOddsCount   =   3;
        $maxOddsCount   =   3;

        $namesMap       =   array('12', '1x', 'x2');

        foreach($Bookmaker->xpath('Odds') AS $Odd) {
            if(!in_array( strtolower ( (string) $Odd->attributes()->bet ), $namesMap)) {
                $DOM = dom_import_simplexml($Odd);
                $DOM->parentNode->removeChild($DOM);
            }
        }

        if( (int) count ( $Bookmaker->children() ) > $maxOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        if( (int) count ( $Bookmaker->children() ) < $minOddsCount) {
            unset($Bookmaker->Odds);
            return $Bookmaker;
        }

        foreach($Bookmaker->xpath('Odds') AS $Odd)
        {
            $Odd->attributes()->addAttribute('name', (string) $Odd->attributes()->bet);
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
        foreach($Outcomes->xpath('Outcome') AS $Outcome) {
            if(!in_array( (string) $Outcome->attributes()->name, $this->validOutcomes)) {
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

        if( file_exists($directory . Inflector::slug($type) . '.xml') )
        {
            if(file_get_contents($directory . Inflector::slug($type) . '.xml') != "")
            {
                try {
                    $xml =  Xml::build($directory . Inflector::slug($type) . '.xml');

                    if($update == false) { return $xml; }

                    if($updateByTimestamp == true) {
                        if( is_object($xml) ) {
                            if( $xml->Header->Timestamp != null ) {
                                $url .= '&timestamp=' . (int) $xml->Header->Timestamp;
                            }
                        }
                    }
                } catch(Exception $e) {

                }
            }
        }
        else{
            if(!file_exists($directory)) {
                mkdir( $directory, 0777, true );
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: en-US,en;q=0.5',
            'Connection: keep-alive',
            'Host: xml.oddservice.com'
        ));

        $data = curl_exec($ch);

        curl_close($ch);

        file_put_contents($directory . Inflector::slug($type) . '.xml', $data);

        return Xml::build($data);
    }
}