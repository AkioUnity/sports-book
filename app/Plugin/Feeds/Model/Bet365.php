<?php

App::uses('FeedsAppModel', 'Feeds.Model');
App::uses('Ticket', 'Model');
App::uses('Sport', 'Model');
App::import('Utility', 'Xml');

class Bet365 extends FeedsAppModel
{
    public $name = 'Bet365';

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

    public $sports=array(
        1,13,18,8,91,78,3,17,12,9,16,15,14,83,36,90,92,19,94,95,110
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

    public function download($name, $type, $url, $update = false, $updateByTimestamp = false)
    {
        $name='upcoming';
        $parmater='sport_id=1&page=1';

        return $this->curl($name,$parmater);
    }

    public function upcoming($sport_id,$page)
    {
        if (sizeof($this->sports)<=$sport_id){
            return null;
        }
        $parameter='sport_id='.$this->sports[$sport_id].'&page='.$page;
        $name='upcoming';
        $data=$this->curl($name,$parameter);
        $results=$data->results;
        if (sizeof($results)>0){
            $data->sport_no=$sport_id;
            return $data;
        }
        else {
            $page=1;
            $sport_id++;
            return $this->upcoming($sport_id,$page);
        }
    }

    public function prematch($event_id)
    {
        $parameter='FI='.$event_id;
        $name='prematch';
        $data=$this->curl($name,$parameter,true);
        $results=$data['results'];
        return $results;
    }

    public function curl($name,$parameter,$assoc=false)
    {
        $token=Configure::read('Bet365token');
        $base_url='https://api.betsapi.com/v1/bet365/';
        if ($name=='prematch')
            $base_url='https://api.betsapi.com/v2/bet365/';
        $url = $base_url.$name.'?token='.$token.'&'.$parameter;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        if ($data === false) {
            $info = curl_getinfo($ch);
            curl_close($ch);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($ch);
        return json_decode($data,$assoc);
    }
}