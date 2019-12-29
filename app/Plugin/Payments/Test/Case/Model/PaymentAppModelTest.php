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

App::uses('PaymentAppModel', 'Model');

class PaymentAppModelTest extends CakeTestCase
{
    /**
     * Test Case Fixtures To Be Loaded
     *
     * @var array
     */
    public $fixtures = array('app.Ticket');

    /**
     * @var PaymentAppModel $_PaymentAppModel
     */
    private $_PaymentAppModel;

    /**
     * Setup the test case, backup the static object values so they can be restored.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->_PaymentAppModel = ClassRegistry::init('PaymentAppModel');
    }

    /**
     * Check user daily stake limits
     *
     * @return void
     */
    public function testSave()
    {
        $this->assertEqual(false, true);
    }
}