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

App::uses('OutcomeFakeProcessor', 'ImportProcessor/Outcomes');
App::uses('Outcome1X2Processor', 'ImportProcessor/Outcomes');
App::uses('OutcomeUnderOverProcessor', 'ImportProcessor/Outcomes');

class OutcomeProcessor {

    public $OutcomeType = null;

    protected $OutcomeProcessors = array(
        '1X2'           =>  'Outcome1X2Processor',
        'Under/Over'    =>  'OutcomeUnderOverProcessor'
    );

    public function __construct($OutcomeType)
    {
        $this->OutcomeType = $OutcomeType;
    }

    // FakeOutcomeProcessor
    public function getOutcomeTypeProcessor()
    {
        if ( is_null($this->OutcomeType) || !isset($this->OutcomeProcessors[$this->OutcomeType]) ) {
           return new OutcomeFakeProcessor('Fake');
        }

        return new $this->OutcomeProcessors[$this->OutcomeType]($this->OutcomeType);
    }
}