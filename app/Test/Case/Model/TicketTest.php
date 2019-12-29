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

App::uses('Ticket', 'Model');
App::import('Model','Bet');
App::import('Model','Group');
App::uses('TicketListener', 'Event');
App::uses('CakeSession', 'Model/Datasource');

class TicketTest extends CakeTestCase {

    /**
     * Test Case Fixtures To Be Loaded
     *
     * @var array
     */
    public $fixtures = array('app.Ticket');

    /**
     * Ticket Model
     *
     * @var Ticket $_Ticket
     */
    private $_Ticket;

    /**
     * Setup the test case, backup the static object values so they can be restored.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->_Ticket = ClassRegistry::init('Ticket');
    }

    /**
     * Check user daily stake limits
     *
     * @return void
     */
    public function testCheckStake()
    {
        $testCases = array(
            /** Days Stake limit: -1, placing stake: 0, expect - Lowest stake error */
            0   =>  array(
                'assert'    => -1,
                'stake'     => Configure::read('Settings.minBet') - 1,
                'expected'  => false
            ),
            /** Days Stake limit: 0, placing stake: 1, expect - True */
            1   =>  array(
                'assert'    => 0,
                'stake'     => Configure::read('Settings.minBet') + 1,
                'expected'  => true
            ),
            /** Days Stake limit: 1, placing stake: 2, expect - Days limit error */
            2   =>  array(
                'assert'    => 1,
                'stake'     => 2,
                'expected'  => false
            ),
        );

        foreach ($testCases AS $testCase) {

            CakeSession::write('Auth.User.day_stake_limit', $testCase['assert']);
            CakeSession::write('Ticket.stake', $testCase['stake']);

            $result = $this->_Ticket->checkStake();

            $this->assertEqual($result['status'], $testCase['expected'],
                __("Maximum days stake are: %.2f, counted more. \r\n Message: %s. \r\n Case: \r\n %s", $testCase['expected'], $result['msg'], var_export($testCase, true))
            );
        }
    }
}