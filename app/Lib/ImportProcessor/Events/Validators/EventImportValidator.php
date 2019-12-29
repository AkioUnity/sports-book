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

App::uses('EventValidatorAbstract', 'ImportProcessor/Events/Validators');

class EventImportValidator extends EventValidatorAbstract
{
    protected $ImportEvent = array();

    protected $assertUniqueEventImportId = null;

    protected $assertUniqueBetImportId = array();

    protected $assertUniqueBetPartImportId = array();

    public function runValidator($importEvent)
    {
        $this->ImportEvent = $importEvent;

        $this->assertUniqueEventImportId($this);

        $this->assertUniqueBetImportId($this);

        $this->assertUniqueBetPartImportId($this);
        var_dump($this);
        exit;

        exit;

        return $this;
    }

    /**
     * @param EventImportValidator $EventImportValidator
     *
     * @return void
     */
    public function assertUniqueEventImportId(EventImportValidator $EventImportValidator)
    {
        $eventImportId = ClassRegistry::init('Event')->dispatchMethod('find', array(
                'all', array(
                    'fields'        =>  array(
                        'Event.id',
                        'Event.import_id'
                    ),
                    'contain'       =>  array(),
                    'conditions'    =>  array(
                        'Event.import_id'  =>  array($EventImportValidator->ImportEvent['Event']['import_id'])
                    )
                )
            )
        );

        $EventImportValidator->assertUniqueEventImportId        =   empty($eventImportId);
        $EventImportValidator->log['assertUniqueEventImportId'] =   $eventImportId;
    }

    /**
     * @param EventImportValidator $EventImportValidator
     *
     * @return void
     */
    public function assertUniqueBetImportId(EventImportValidator $EventImportValidator)
    {
        $betImportId = ClassRegistry::init('Bet')->dispatchMethod('find', array(
                'all', array(
                    'fields'        =>  array(
                        'Bet.id',
                        'Bet.import_id'
                    ),
                    'contain'       =>  array(),
                    'conditions'    =>  array(
                        'Bet.import_id'  =>  array_map(function($Bet){ return $Bet['import_id']; }, $EventImportValidator->ImportEvent['Bet'])
                    )
                )
            )
        );

        $EventImportValidator->assertUniqueBetImportId          =   empty($betImportId);
        $EventImportValidator->log['assertUniqueBetImportId']   =   $betImportId;
    }

    /**
     * @param EventImportValidator $EventImportValidator
     *
     * @return void
     */
    public function assertUniqueBetPartImportId(EventImportValidator $EventImportValidator)
    {
        $betPartImportId = array();

        foreach ($EventImportValidator->ImportEvent['Bet'] AS $Bet) {
            $betPartImportId[] = ClassRegistry::init('Bet')->dispatchMethod('find', array(
                    'all', array(
                        'fields'        =>  array(
                            'Bet.id',
                            'Bet.import_id'
                        ),
                        'contain'       =>  array(),
                        'conditions'    =>  array(
                            'Bet.import_id'  =>  array_map(function($BetPart){ return $BetPart['import_id']; }, $Bet['BetPart'])
                        )
                    )
                )
            );
        }

        $EventImportValidator->assertUniqueBetPartImportId          =   (in_array(false, array_map(function($Result) { return empty($Result); }, $betPartImportId)) === false);
        $EventImportValidator->log['assertUniqueBetPartImportId']   =   $betPartImportId;
    }
}