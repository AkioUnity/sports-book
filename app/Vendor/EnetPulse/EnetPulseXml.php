<?php

define('ENET_ROOT', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__))));

require_once(ENET_ROOT . DS . 'XMLParser.php');

class EnetPulseXml {

    public $name = 'EnetPulseXml';

    public static $feedTypes = array(
        'ObjectQueryFeed'   =>  array('initialized' => false, 'name' => 'ObjectQueryFeed', 'object' => null),
        'OddsFeed'          =>  array('initialized' => false, 'name' => 'OddsFeed',        'object' => null)
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

    public function processFeed($xml = "")
    {
        foreach(self::$feedTypes AS $feedType) {
            if ($feedType['object']->validXmlObjectType($xml)) {
                $feedType['object']->parseFeedXml($xml);
            }
        }
    }
}