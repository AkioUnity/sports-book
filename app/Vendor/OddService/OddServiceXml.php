<?php

define('ODD_ROOT', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__))));

require_once(ODD_ROOT . DS . 'XMLParser.php');

class OddServiceXml {

    public $name = 'OddServiceXml';

    public static $feedTypes = array(
        'InPlay'    =>  array('initialized' => false, 'name' => 'InPlay', 'object' => null),
        'PreMatch'  =>  array('initialized' => false, 'name' => 'PreMatch', 'object' => null)
    );

    /**
     * Constructor initializes xml parser
     */
    public function __construct()
    {
        foreach (self::$feedTypes AS $index => $feedType) {
            if ($feedType['initialized'] == false) {

                require_once(str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__))) . DS . 'Feeds' . DS . $feedType["name"] . '.php');

                $class = $this->name . $feedType["name"];

                self::$feedTypes[$index]['object']      =   new $class();
                self::$feedTypes[$index]['initialized'] =   true;
            }
        }
    }

    public function getFeedTypeInstance($type)
    {
        return self::$feedTypes[$type]["object"];
    }

    public function processFeed($xml = "")
    {
        foreach(self::$feedTypes AS $feedType) {
            if ($feedType['object']->validXmlObjectType($xml)) {
                $feedType['object']->parseFeedXml($xml);
            }
        }
    }
}