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
App::import('Vendor', 'EnetPulse', array('file' => 'EnetPulse/EnetPulseXml.php'));
App::import('Utility', 'Xml');

class EnetPulseShell extends FeedAppShell implements FeedShell
{
    /**
     * Returns self instance
     *
     * @var EnetPulseShell|null $_instance
     */
    private static $_instance = null;

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Feeds.EnetPulse', 'Country', 'Sport', 'League', 'Event', 'Bet', 'BetPart', 'Setting', 'Currency', 'Ticket');

    /**
     * http://spocosy.enetpulse.com/xmlservice/xmlservice.php?&service=countries&usr=tstchlk&pwd=47895hj&requestid=&xsl=&includeProperties=0&includeDebug=0&GZIP_XML=
     *
     * @return mixed|void
     */
    public function importCountries()
    {
        $data  =   APP . 'tmp' . DS . 'xml' . DS . $this->name . DS . 'countries.xml';

        try {
            $xml = Xml::build($data);

            foreach ($xml->{'query-response'}->countries->country AS $Country) {
                $CountryImportId    =   $Country->attributes()->id->__toString();
                $CountryName        =   $Country->attributes()->name->__toString();

                $this->Country->insertCountry($CountryImportId, $CountryName);

                $this->out(__("#%d - %s", $CountryImportId, $CountryName));
            }
        } catch (Exception $e) {
            CakeLog::critical($e->getMessage(), $this->name);
        }
    }

    /**
     * http://spocosy.enetpulse.com/xmlservice/xmlservice.php?&service=sportlist&usr=tstchlk&pwd=47895hj&requestid=&xsl=&includeProperties=0&includeDebug=0&GZIP_XML=Leagues
     *
     * @return mixed|void
     */
    public function importSports()
    {
        $data  =   APP . 'tmp' . DS . 'xml' . DS . $this->name . DS . 'sports.xml';

        try {
            $xml = Xml::build($data);

            foreach ($xml->{'query-response'}->sports->sport AS $Sport) {

                $SportImportId  = $Sport->attributes()->id->__toString();;
                $SportName      = $Sport->attributes()->name->__toString();;

                $this->Sport->insertSport($SportImportId, $SportName, self::FEED_PROVIDER);

                $this->out(__("#%d - %s", $SportImportId, $SportName));
            }
        } catch (Exception $e) {
            $this->out($e);
        }
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

    public function parseXml()
    {
        $this->out(__("Parsing xml start!"));

        $limit      =   50;
        $i          =   0;

        $queryCount = $this->EnetPulse->find('count', array(
            'conditions'    =>  array(
                'EnetPulse.state'   =>  1
//                'EnetPulse.id'   =>  275
            )
        ));

        $count  = (string) ceil($queryCount / $limit);

        $this->out(__("Total objects count: %d", $queryCount));
        $this->out(__("Total queries count: %d", $count));

        $EnetPulseXml   =   new EnetPulseXml();
        $Directory      =   $this->EnetPulse->getXmlDirectory();

        while($count > $i) {
            $this->out(__("i: %d; Offset: %d; Limit: %d", $i, $i * $limit, $limit));
            $xmlList        =   $this->EnetPulse->find('all', array(
                'conditions'    =>  array(
                    'EnetPulse.state'   =>  1
//                    'EnetPulse.id'   =>  275
                ),
                'limit'             =>  $limit,
                'offset'            =>  $i * $limit,
                'order'         =>  array('EnetPulse.id ASC')
            ));

            foreach ($xmlList AS $index => $xml) {
                try {
                    $xmlFile = $Directory . $xml[$this->name]["id"] . ".xml";

                    if (!is_readable($xmlFile)) {
                        throw new Exception("Can't find xml file!", 500);
                    }

                    $this->out(__("Parsing xml by id: %d", $xml[$this->name]["id"]));

                    $EnetPulseXml->processFeed(file_get_contents($xmlFile));

                    // $this->EnetPulse->setState($xml[$this->name]["id"], 2); // TODO: revert

                } catch (Exception $e) {
                    var_dump($e);
                    $this->EnetPulse->setState($xml[$this->name]["id"], 3);
                    CakeLog::critical($e->getMessage(), $this->name);
                }

                unset($xmlList[$index]);
            }

            $i++;
        }
    }
}