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

App::uses('User', 'Model');

class UserTest extends CakeTestCase
{

    /**
     * Test Case Fixtures To Be Loaded
     *
     * @var array
     */
    public $fixtures = array('app.User');

    /**
     * User Model
     *
     * @var User $_User
     */
    private $_User;

    /**
     * Setup the test case, backup the static object values so they can be restored.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->_User = ClassRegistry::init('User');
    }

    public function testUpdateLastVisit()
    {

    }
}