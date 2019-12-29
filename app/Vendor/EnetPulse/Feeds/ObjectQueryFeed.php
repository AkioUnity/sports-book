<?php
 /**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

class EnetPulseXmlObjectQueryFeed
{
    const FEED_PROVIDER = 'OddService';

    public static $_xmlParseObjects = array();

    public static $_xmlObjectParsers = array();

    /** @var  XMLParser $_XMLParser */
    private static $_XMLParser;

    public function __construct()
    {
        self::$_XMLParser = new XMLParser();
    }

    /**
     * Checks is xml string valid for this feed query feed parser
     *
     * @param string $xml
     *
     * @return bool
     */
    public function validXmlObjectType($xml = "")
    {
       //  return false; // TODO: revert

        $matches = array();

        foreach (array('outcome', 'bettingoffer') AS $xmlTag) {
            $matches[] = strpos($xml, $xmlTag);
        }

        $diff = array_diff($matches, array(false, false));

        return empty($diff);
    }

    public function parseFeedXml($xmlContent)
    {
        $xmlData        =   self::$_XMLParser->parseFeed($xmlContent);
                            self::$_XMLParser->reset();

        $objectsTypes   =   $xmlData[0];
        $attributes     =   $xmlData[1];
        $objectsData    =   array();

        /** Build array by objects type */
        foreach ($objectsTypes AS $i => $objectType) {
            $objectsData[$objectType][] = $attributes[$i];
            unset($attributes[$i]);
            unset($objectsTypes[$i]);
        }

        EnetPulseShell::getInstance()->out(__('OddsFeed Import Start. Total Objects ( %d ) To Process', $objectsData));

        try {
            foreach ($objectsData AS $objectType => $objectData) {
                self::loadObjectClass($objectType)->initObject($objectData);

            }
            foreach ($objectsData AS $objectType => $objectData) {
                self::loadObjectClass($objectType)->processObject($objectData);
            }
            foreach ($objectsData AS $objectType => $objectData) {
                self::loadObjectClass($objectType)->reset($objectData);
            }
        } catch (Exception $e) {
            var_dump($e);
            CakeLog::critical($e->getMessage(), 'EnetPulse');
        }

        unset($xmlData);
        unset($objectsTypes);
        unset($attributes);
    }

    public static function loadObjectClass($objectType)
    {
        try {
            if (!self::objectLoaded($objectType)) {
                $file = str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__))) . DS . 'ObjectQueryFeed' . DS . $objectType . '.php';
                if(!is_readable($file)) {
                    throw new Exception(sprintf("File %s does not exist!", $file), 500);
                }
                require_once($file);
                $className                              =   'EnetPulseXmlObjectQueryFeedParser_' . $objectType;
                self::$_xmlObjectParsers[$objectType]   =   new $className(self::$_XMLParser->attributes[$objectType]);
            }
        } catch (Exception $e) {
            var_dump($e);
            return self::loadObjectClass('fake');
        }

        return self::$_xmlObjectParsers[$objectType];
    }

    public static function objectLoaded($objectName)
    {
        return isset(self::$_xmlObjectParsers[$objectName]);
    }
}