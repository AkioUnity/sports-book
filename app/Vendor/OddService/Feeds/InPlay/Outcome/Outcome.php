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

App::uses('Result', 'Events.Model');
App::uses('Participant', 'Events.Model');

class OddService_ObjectParser_OutcomeTypeProcessor
{
    /**
     * Outcome class name
     *
     * @var string $name
     */
    public $name        =   'OutcomeProcessor';

    /**
     * Participant Model
     *
     * @var Participant $Participant
     */
    public  $Participant;

    /**
     * Event Model
     *
     * @var Event $Event
     */
    public $Event;

    /**
     * Result Model
     *
     * @var Result $Result
     */
    public $Result;

    /**
     * Tmp data
     *
     * @var array $_tmp
     */
    private $_tmp       =   array();

    /**
     * Initializes required classes, data
     *
     */
    public function __construct()
    {
        $this->Participant  =   new Participant();
        $this->Event        =   new Event();
        $this->Result       =   new Result();
    }

    /**
     * Returns betting type import ids
     *
     * @param $betItemSet
     *
     * @return array - import ids
     */
    public function getImportIds($betItemSet)
    {
        return array_map(function($item){ return $item["id"]; }, $betItemSet);
    }

    /**
     * Return event import id from
     * given odds set
     *
     * @param $betSet
     *
     * @return string|int
     */
    public function getEventImportId($betSet)
    {
        return $betSet[0]["objectFK"];
    }

    /**
     * Returns bet part order by name
     *
     * @param string $name
     * @param array  $orderNames
     *
     * @return mixed|string
     */
    public function getOrder($name, array $orderNames)
    {
        $orderNames = array_map( function($item) {
            return strtolower($item);
        }, $orderNames);
        return array_search( strtolower( $name ), $orderNames) + 1;
    }

    public function tmpAdd($key, $value)
    {
        $this->_tmp[$key] = $value;
    }

    public function tmpExists($key)
    {
        return isset($this->_tmp[$key]);
    }

    public function tmpGet($key)
    {
        if ($this->tmpExists($key)) {
            return $this->_tmp[$key];
        }
        return null;
    }

    public function tmpDelete($key)
    {
        if ($this->tmpExists($key)) {
            unset($this->_tmp[$key]);
        }
    }

    public function tmpFlush()
    {
        $this->_tmp = array();
    }

    public function getScope($betSet)
    {
        return $betSet[0]["scope"];
    }

    /**
     * Returns Bet Name By Participant
     *
     * @param array $item
     * @param bool  $flush
     *
     * @return bool
     * @throws Exception
     */
    public function getName(array $item, $flush = false)
    {
        $key    =   md5('getName' . $this->name . __FUNCTION__);

        if ($flush == true) {
            $this->tmpDelete($key);
            return true;
        }

        $hash  =   md5($item["id"] . $item["objectFK"] . $item["scope"]);

        $values =   $this->tmpGet($key);

        if (!isset($values[$hash])) {
            $Event              =   $this->Event->find("first", array(
                "contain"       =>  array(),
                "fields"        =>  array("Event.id"),
                "conditions"    =>  array(
                    "Event.import_id" => $item["objectFK"],
                )
            ));

            if (empty($Event)) {
                throw new Exception('Event not found!', 500);
            }

            $Participant        =   $this->Participant->find("first", array(
                "contain"       =>  array(),
                "fields"        =>  array("Participant.number"),
                "conditions"    =>  array(
                    "Participant.import_id" => $item["iparam"],
                    "Participant.event_id"  => $Event["Event"]["id"]
                )
            ));

            if (empty($Participant)) {
                throw new Exception('Participant not found!', 500);
            }

            # var_dump( $this->name . '#' . $item["id"] . ' ' . $Event["Event"]["id"] . ':' . $item["iparam"] . ' - ' . $Participant["Participant"]["number"]);

            $this->tmpAdd($key, array($hash => $Participant["Participant"]["number"]));

            return $Participant["Participant"]["number"];
        }

        return $values[$hash];
    }

    /**
     * @param $participantImportId
     *
     * @return array
     */
    public function getParticipantIdImportId($participantImportId)
    {
        return $this->Participant->find('first', array(
            'contain'       =>  array(),
            'fields'        =>  array('id'),
            'conditions'    =>  array(
                'Participant.import_id' =>  $participantImportId
            )
        ));
    }
}