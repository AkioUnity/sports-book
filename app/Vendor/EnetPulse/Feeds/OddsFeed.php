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

class EnetPulseXmlOddsFeed
{
    public static $_xmlParseObjects = array();

    public static $_xmlObjectParsers = array();

    /** @var  XMLParser $_XMLParser */
    private static $_XMLParser;

    public function __construct()
    {
        self::$_XMLParser = new XMLParser();
    }

    /**
     * Checks is xml string valid for this feed odds feed parser
     *
     * @param string $xml
     *
     * @return bool
     */
    public function validXmlObjectType($xml = "")
    {
        return false; // TODO: revert
        foreach (array('outcome', 'bettingoffer') AS $xmlTag) {
            if (strpos($xml, $xmlTag) !== false) {
                return true;
            }
        }
        return false;
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
                self::loadObjectClass($objectType)->parseObject($objectData);
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
        if (!self::objectLoaded($objectType)) {
            require_once(str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__))) . DS . 'OddsFeed' . DS . $objectType . '.php');

            $className                              =   'EnetPulse_ObjectParser_' . $objectType;
            self::$_xmlObjectParsers[$objectType]   =   new $className(self::$_XMLParser->attributes[$objectType]);

            if (!method_exists($className, 'parseObject')) {
                EnetPulseShell::getInstance()->out('OddsFeed :: EnetPulse_ObjectParser_' . $objectType . '->parseObject();');
            }
        }

        return self::$_xmlObjectParsers[$objectType];
    }

    public static function objectLoaded($objectName)
    {
        return isset(self::$_xmlObjectParsers[$objectName]);
    }
} 