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

class AppControllerTest extends ControllerTestCase
{
    public $fixtures = array(
        0   =>  'app.Setting',
        1   =>  'app.Currency',
        2   =>  'app.User',
        3   =>  'app.Language',
        4   =>  'app.Ticket',
        5   =>  'app.Group'
    );

    public $autoFixtures = true;

    /**
     * Empty Test Case
     *
     * @return void
     */
    public function testBeforeFilter()
    {

    }

    /**
     * Tear down any static object changes and restore them.
     * Override original method to keep case data (sessions, etc)
     *
     * @return void
     */
    public function tearDown() { }
}