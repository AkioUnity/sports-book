<?php

Class OddServiceXmlPreMatch
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
                $file = str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__))) . DS . 'PreMatch' . DS . $objectType . '.php';
                if(!is_readable($file)) {
                    throw new Exception(sprintf("File %s does not exist!", $file), 500);
                }
                require_once($file);
                $className                              =   'OddServiceXmlPreMatchParser_' . $objectType;
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
        foreach (array('Results') AS $xmlTag) {
            if (strpos($xml, $xmlTag) !== false) {
                return true;
            }
        }
        return false;
    }
}