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

class EventUpdateValidator extends EventValidatorAbstract
{
    protected $assertUniqueEventImportId = null;

    protected $assertEqualEventsImportIds = null;

    protected $assertUniqueBetImportId = array();

    protected $assertEqualBetsImportIds = array();

    protected $assertUniqueBetPartImportId = array();

    protected $assertEqualBetPartsImportIds = array();

    public function runValidator($importEvent, $importedEvent)
    {
        $assertUniqueEventImportIds = $this->assertUniqueEventImportId($importEvent['Event']['import_id']);

//        var_dump($assertUniqueEventImportIds);

        exit;

//        $this->assertUniqueBetImportId($importEvent['Bet']['import_id']);
//
//        $this->assertUniqueBetPartImportId();

        return $this;
    }

    public function getResult()
    {

    }
}