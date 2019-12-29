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

App::uses('HttpSocket', 'Network/Http');

App::uses('Ticket', 'Model');

class TicketsControllerTest extends ControllerTestCase {

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        0   =>  'app.Setting',
        1   =>  'app.Currency',
        2   =>  'app.User',
        3   =>  'app.Language',
        4   =>  'app.Ticket',
        5   =>  'app.Group',
        6   =>  'app.TicketPart'
    );

    /**
     * Control table create/drops on each test method.
     *
     * @var bool
     */
    public $dropTables = false;

    /**
     * HttpSocket Object
     *
     * @var HttpSocket $HttpSocket
     */
    private $_HttpSocket;

    /**
     * Setup the test case, backup the static object values so they can be restored.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->_HttpSocket = new HttpSocket();
    }

    public function testPlace()
    {
        $this->generate('Tickets', array(
            'components' => array(
                'Security' => array('_validateCsrf', 'blackHole')
            )
        ));
    }

    /**
     * Tests getHistory()
     *
     * @return void
     */
    public function testGetHistory()
    {
        $this->generate('Tickets', array(
            'components' => array(
                'Security' => array('_validateCsrf', 'blackHole')
            )
        ));

        $result = $this->_testAction(
            '/tickets/getHistory',
            array('return' => 'vars')
        );

        $this->assertEqual(is_array($result['lastTickets']), true);
    }

    /**
     * Tear down any static object changes and restore them.
     * Override original method to keep case data (sessions, etc)
     *
     * @return void
     */
    public function tearDown() { }
}