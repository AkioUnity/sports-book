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

class OddServiceXmlInPlay
{
    public static $_xmlParseObjects = array();

    public static $_xmlObjectParsers = array();

    /** @var  XMLParser $_XMLParser */
    private static $_XMLParser;

    public function __construct()
    {
        self::$_XMLParser = new XMLParser();
    }

    public static function loadObjectClass($objectType)
    {
        try {
            if (!self::objectLoaded($objectType)) {
                $file = str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__))) . DS . 'InPlay' . DS . $objectType . '.php';
                if(!is_readable($file)) {
                    throw new Exception(sprintf("File %s does not exist!", $file), 500);
                }
                require_once($file);
                $className                              =   'OddServiceXmlInPlayParser_' . $objectType;
                self::$_xmlObjectParsers[$objectType]   =   new $className();
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

    /**
     * Checks is xml string valid for this feed odds feed parser
     *
     * @param string $xml
     *
     * @return bool
     */
    public function validXmlObjectType($xml = "")
    {
        foreach (array('Event') AS $xmlTag) {
            if (strpos($xml, $xmlTag) !== false) {
                return true;
            }
        }
        return false;
    }

    public function parseFeedXml($xmlContent)
    {
        $Event        =   Xml::build($xmlContent);

        try {
            self::loadObjectClass('Event')->initObject(array(Xml::toArray($Event)));

            self::loadObjectClass('Event')->processObject();

            self::loadObjectClass('Event')->reset();
        } catch (Exception $e) {
            var_dump($e);
            CakeLog::critical($e->getMessage(), 'OddService');
        }

        unset($xmlData);
        unset($objectsTypes);
        unset($attributes);
    }
} 