<?php

App::uses('AppHelper', 'View/Helper');
App::uses('TimeZoneComponent', 'Controller/Component');

class TimeZoneHelper extends AppHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'TimeZone';

    /**
     * Helpers list
     *
     * @var array
     */
    public $helpers = array('Form', 'Time', 'Session');

    /** @var TimeZoneComponent $_TimeZoneComponent  */
    private $_TimeZoneComponent;

    /**
     * Time zone config
     *
     * @var array
     */
    public $timeZones = array();

    /**
     * Default Constructor
     *
     * @param View  $View     - The View this helper is being attached to.
     * @param array $settings - Configuration settings for the helper.
     */
    public function __construct(View $View, $settings = array())
    {
        $this->_TimeZoneComponent = new TimeZoneComponent(new ComponentCollection());
        parent::__construct($View, $settings);
    }

    /**
     * Returns time zones
     *
     * @param bool $returnValues
     *
     * @return array
     */
    public function getTimeZones($returnValues = false)
    {
        return $this->_TimeZoneComponent->getTimeZones($returnValues);
    }

    /**
     * Returns timezone by code
     *
     * @param $code
     *
     * @return bool
     */
    public function timeZoneByCode($code)
    {
        return $this->_TimeZoneComponent->timeZoneByCode($code);
    }

    public function timeZoneCode()
    {
        return $this->_TimeZoneComponent->timeZoneCode();
    }

    /**
     * Returns current timezone
     *
     * @return string
     */
    public function timeZone() {
        return $this->_TimeZoneComponent->timeZone();
    }

    /**
     * Returns gmt timestamp
     *
     * @return int
     */
    public function getGMTTime() {
        return $this->_TimeZoneComponent->getGMTTime();
    }

    /**
     * Returns localtime
     *
     * @return bool|string
     */
    public function getLocalTime() {
        return $this->_TimeZoneComponent->getLocalTime();
    }

    /**
     * Converts and returns from local to gmt
     *
     * @param $localTime
     *
     * @return bool|string
     */
    public function localToGMT($localTime) {
        return $this->_TimeZoneComponent->localToGMT($localTime);
    }

    /**
     * Converts and returns from gmt to local
     *
     * @param $localTime
     *
     * @return bool|string
     */
    public function GMTToLocal($localTime) {
        return $this->_TimeZoneComponent->GMTToLocal($localTime);
    }

    /**
     * Converts by offset
     *
     * @param $time
     * @param $offset
     *
     * @return bool|string
     */
    public function convert($time, $offset) {
        return $this->_TimeZoneComponent->convert($time, $offset);
    }

    /**
     * Converts time to user timezone time
     *
     * @param $time
     * @param string $format
     * @param null $TimeZone
     *
     * @return mixed
     */
    public function convertTime($time, $format = 'H:i', $TimeZone = null)
    {
        return $this->_TimeZoneComponent->convertTime($time, $format, $TimeZone);
    }

    /**
     * Converts date to user timezone date
     *
     * @param $time
     * @param string $format
     * @param null $TimeZone
     *
     * @return mixed
     */
    public function convertDate($time, $format = 'H:i d\/m\/Y', $TimeZone = null)
    {
        return $this->_TimeZoneComponent->convertDate($time, $format, $TimeZone);
    }

    /**
     * Converts datetime to user timezone datetime
     * @param $time
     *
     * @return mixed
     */
    public function convertDateTime($time) {
        return $this->_TimeZoneComponent->convertDateTime($time);
    }

    /**
     * Converts and returns diff between timezones
     *
     * @param $value
     * @param $from_timezone
     * @param $to_timezone
     * @param string $format
     *
     * @return string
     */
    function convertTimeZone($value, $from_timezone, $to_timezone, $format = 'Y-m-d H:i:s')
    {
        return $this->_TimeZoneComponent->convertTimeZone($value, $from_timezone, $to_timezone, $format);
    }

    /**
     * Returns time diff
     *
     * @param $time
     * @param bool $suffix
     *
     * @return string
     */
    public function getRemainingTime($time, $suffix = true)
    {
        return $this->_TimeZoneComponent->getRemainingTime($time, $suffix);
    }
}