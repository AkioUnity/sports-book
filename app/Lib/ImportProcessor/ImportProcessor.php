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

App::uses('EventProcessor', 'ImportProcessor/Events');

class ImportProcessor
{
    public $Events = null;

    public static $Validators = array();

    public static function setEvent()
    {
        return new EventProcessor();
    }

    public static function setValidator(array $importEvent, array $importedEvent)
    {
        if (empty($importedEvent)) {
            App::uses('EventImportValidator', 'ImportProcessor/Events/Validators');
            $Validator = new EventImportValidator();
        } else {
            App::uses('EventUpdateValidator', 'ImportProcessor/Events/Validators');
            $Validator = new EventUpdateValidator();
        }

        self::$Validators[] = $Validator->runValidator($importEvent, $importedEvent);

        return end(self::$Validators);
    }

    public function getEvent($id = null)
    {

    }
}