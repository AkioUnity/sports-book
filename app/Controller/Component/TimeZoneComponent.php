<?php
/**
 * TimeZone Component
 *
 * This is base TimeZone Component Class
 * Class cloned also as TimeZone helper
 * Class provides reusable bits of controller logic that can be composed into another controllers.
 *
 * @package     App.Controller
 * @author      Deividas Petraitis <deividas@laiskai.lt>
 * @copyright   2013 The ChalkPro Betting Scripts
 * @license     http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version     Release: @package_version@
 * @link        http://www.chalkpro.com/
 * @see         Controller::$components
 */

App::uses('Component', 'Controller');

class TimeZoneComponent extends Component
{
    /**
     * Other Components this component uses.
     *
     * @var array
     */
    public $components = array('Session');

    /**
     * Time zone config
     *
     * @var array
     */
    public $timeZones = array(
        '-12.0' =>  array(
            'translation'   => '(GMT -12:00 hours) Eniwetok, Kwajalein',
            'TimeZone'      => 'Pacific/Kwajalein',
            'UTC'           =>  'UTC-1200'
        ),
        '-11.0' =>  array(
            'translation'   => '(GMT -11:00 hours) Midway Island, Somoa',
            'TimeZone'      => 'Pacific/Samoa',
            'UTC'           =>  'UTC-1100'
        ),
        '-10.0' =>  array(
            'translation'   => '(GMT -10:00 hours) Hawaii',
            'TimeZone'      => 'Pacific/Honolulu',
            'UTC'           =>  'UTC-1000'
        ),
        '-9.0'  =>  array(
            'translation'   => '(GMT -9:00 hours) Alaska',
            'TimeZone'      => 'America/Juneau',
            'UTC'           =>  'UTC-0900'
        ),
        '-8.0'  =>  array(
            'translation'   => '(GMT -8:00 hours) Pacific Time (US & Canada)',
            'TimeZone'      => 'America/Los_Angeles',
            'UTC'           =>  'UTC-0800'
        ),
        '-7.0'  =>  array(
            'translation'   => '(GMT -7:00 hours) Mountain Time (US & Canada)',
            'TimeZone'      => 'America/Denver',
            'UTC'           =>  'UTC-0700'
        ),
        '-6.0'  =>  array(
            'translation'   => '(GMT -6:00 hours) Central Time (US & Canada), Mexico City',
            'TimeZone'      => 'America/Mexico_City',
            'UTC'           =>  'UTC-0600'
        ),
        '-5.0'  =>  array(
            'translation'   => '(GMT -5:00 hours) Eastern Time (US & Canada), Bogota, Lima, Quito',
            'TimeZone'      => 'America/New_York',
            'UTC'           =>  'UTC-0500'
        ),
        '-4.0'  =>  array(
            'translation'   => '(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz',
            'TimeZone'      => 'America/Caracas',
            'UTC'           =>  'UTC-0900'
        ),
        '-3.5'  =>  array(
            'translation'   => '(GMT -3:30 hours) Newfoundland',
            'TimeZone'      => 'America/St_Johns',
            'UTC'           =>  'UTC-3.500'
        ),
        '-3.0'  =>  array(
            'translation'   => '(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown',
            'TimeZone'      => 'America/Argentina/Buenos_Aires',
            'UTC'           =>  'UTC-3000'
        ),
        '-2.0'  =>  array(
            'translation'   => '(GMT -2:00 hours) Mid-Atlantic',
            'TimeZone'      => 'Atlantic/Azores',
            'UTC'           =>  'UTC-2000'
        ),
        '-1.0'  =>  array(
            'translation'   => '(GMT -1:00 hours) Azores, Cape Verde Islands',
            'TimeZone'      => 'Atlantic/Azores'
        ),
        '0.00'  =>  array(
            'translation'   => '(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia',
            'TimeZone'      => 'Europe/London'
        ),
        '1.0'  =>  array(
            'translation'   => '(GMT +1:00 hours) CET(Central Europe Time), Brussels, Copenhagen, Madrid, Paris',
            'TimeZone'      => 'Europe/Paris'
        ),
        '2.0'  =>  array(
            'translation'   => '(GMT +2:00 hours) EET(Eastern Europe Time), Kaliningrad, South Africa',
            'TimeZone'      => 'Europe/Helsinki'
        ),
        '3.0'  =>  array(
            'translation'   => '(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg, Volgograd, Nairobi',
            'TimeZone'      => 'Europe/Moscow'
        ),
        '3.5'  =>  array(
            'translation'   => '(GMT +3:30 hours) Tehran',
            'TimeZone'      => 'Asia/Tehran'
        ),
        '4.0'  =>  array(
            'translation'   => '(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi',
            'TimeZone'      => 'Asia/Baku'
        ),
        '4.5'  =>  array(
            'translation'   => '(GMT +4:30 hours) Kabul',
            'TimeZone'      => 'Asia/Kabul'
        ),
        '5.0'  =>  array(
            'translation'   => '(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent',
            'TimeZone'      => 'Asia/Karachi'
        ),
        '5.5'  =>  array(
            'translation'   => '(GMT +5:30 hours) Bombay, Calcutta, Madras, New Delhi',
            'TimeZone'      => 'Asia/Calcutta'
        ),
        '6.0'  =>  array(
            'translation'   => '(GMT +6:00 hours) Almaty, Dhaka, Colombo',
            'TimeZone'      => 'Asia/Colombo'
        ),
        '7.0'  =>  array(
            'translation'   => '(GMT +7:00 hours) Bangkok, Hanoi, Jakarta',
            'TimeZone'      => 'Asia/Bangkok'
        ),
        '8.0'  =>  array(
            'translation'   => '(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong, Chongqing, Urumqi, Taipei',
            'TimeZone'      => 'Asia/Singapore'
        ),
        '9.0'  =>  array(
            'translation'   => '(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
            'TimeZone'      => 'Asia/Tokyo'
        ),
        '9.5'  =>  array(
            'translation'   => '(GMT +9:30 hours) Adelaide, Darwin',
            'TimeZone'      => 'Australia/Adelaide'
        ),
        '10.0' =>  array(
            'translation'   => '(GMT +10:00 hours) EAST(East Australian Standard), Guam, Papua New Guinea, Vladivostok',
            'TimeZone'      => 'Pacific/Guam'
        ),
        '11.0' =>  array(
            'translation'   => '(GMT +11:00 hours) Magadan, Solomon Islands, New Caledonia',
            'TimeZone'      => 'Asia/Magadan'
        ),
        '12.0' =>  array(
            'translation'   => '(GMT +12:00 hours) Auckland, Wellington, Fiji, Kamchatka, Marshall Island',
            'TimeZone'      => 'Asia/Kamchatka'
        )
    );

    /**
     * Returns time zones
     *
     * @param bool $returnValues
     * @return array
     */
    public function getTimeZones($returnValues = false) {
        if(!$returnValues) {
            $timeZones = array();
            foreach($this->timeZones AS $key => $timeZone) {
                $timeZones[$key] = $timeZone['translation'];
            }
            return $timeZones;
        }
        return $this->timeZones;
    }

    /**
     * Returns timezone by code
     *
     * @param $code
     * @return bool
     */
    public function timeZoneByCode($code)
    {
        if(!isset($this->timeZones[$code])) {
            return false;
        }
        return $this->timeZones[$code];
    }

    public function timeZoneCode() {
        if ($this->Session->check('Auth.User.time_zone')) {
            $timeZone = $this->Session->read('Auth.User.time_zone');
        } elseif (!is_null(Configure::Read('Settings.defaultTimezone'))) {
            $timeZone = Configure::Read('Settings.defaultTimezone');
        } else {
            $timeZone = '0.00';
        }

        return $timeZone;
    }
    /**
     * Returns current timezone
     *
     * @return string
     */
    public function timeZone() {
        $timeZone = $this->timeZoneByCode($this->timeZoneCode());

        if(!$timeZone) { return $this->timeZones['0.00']['TimeZone']; }

        return $timeZone['TimeZone'];
    }

    /**
     * Returns gmt timestamp
     *
     * @return int
     */
    public function getGMTTime() {
        return strtotime(gmdate('Y-m-d H:i:s'));
    }

    /**
     * Returns localtime
     *
     * @return bool|string
     */
    public function getLocalTime() {
        return $this->GMTToLocal($this->getGMTTime());
    }

    /**
     * Converts and returns from local to gmt
     *
     * @param $localTime
     * @return bool|string
     */
    public function localToGMT($localTime) {
        return $this->convert($localTime, - $this->timeZone());
    }

    /**
     * Converts and returns from gmt to local
     *
     * @param $localTime
     * @return bool|string
     */
    public function GMTToLocal($localTime) {
        return $this->convert($localTime, $this->timeZone());
    }

    /**
     * Converts by offset
     *
     * @param $time
     * @param $offset
     * @return bool|string
     */
    public function convert($time, $offset) {
        return date('Y-m-d H:i', $time + $offset * 60 * 60);
    }

    /**
     * Converts time to user timezone time
     *
     * @param $time
     * @param string $format
     * @param null $TimeZone
     * @return mixed
     */
    public function convertTime($time, $format = 'H:i', $TimeZone = null) {
        $TimeZone = $TimeZone == null ? new DateTimeZone($this->timeZone()) : $TimeZone;
        return CakeTime::format($format, $time, null, $TimeZone);

    }

    /**
     * Converts date to user timezone date
     *
     * @param $date
     * @param string $format
     * @param null $TimeZone
     * @return mixed
     */
    public function convertDate($date, $format = 'H:i d\/m\/Y', $TimeZone = null)
    {
        $date2 = new DateTime($date, new DateTimeZone('UTC'));
        $date2->setTimezone(new DateTimeZone($this->timeZone()));

        return $date2->format($format);
    }

    /**
     * Converts and returns diff between timezones
     *
     * @param $value
     * @param $from_timezone
     * @param $to_timezone
     * @param string $format
     * @return string
     */
    function convertTimeZone($value, $from_timezone, $to_timezone, $format = 'Y-m-d H:i:s') {
        $dateTime = new DateTime($value, new DateTimeZone($from_timezone));
        $dateTime->setTimezone(new DateTimeZone($to_timezone));
        return $dateTime->format($format);
    }

    /**
     * Converts datetime to user timezone datetime
     * @param $time
     * @return mixed
     */
    public function convertDateTime($time) {
        return CakeTime::format('d\/m\/Y H:i', $time, null, $this->timeZone());
    }

    /**
     * Returns time diff
     *
     * @param $time
     * @param bool $suffix
     * @return string
     */
    public function getRemainingTime($time, $suffix = true)
    {
        $difference = strtotime($time) - strtotime(gmdate("M d Y H:i:s"));
        $sDays = $sHours = $sMins = '';
        $rDays = date('j', $difference) - 1;

        if ($rDays > 0) {
            $sDays = $rDays;

            if($suffix) {
                $sDays .= ' ' . $rDays > 1 ? __('days') : __('day'). ' ';
            }
        }

        $rHours = date('G', $difference);

        if ($rHours > 0) {
            $sHours = $rHours;

            if($suffix) {
                $sHours .= ' ' . __('h') . ' ';
            }
        }

        $rMins = (int) date('i', $difference);

        if ($rMins > 0) {
            $sMins = $rMins;

            if($suffix) {
                $sMins .= ' ' . __('min');
            }
        }

        return $sDays . $sHours . $sMins;
    }
}