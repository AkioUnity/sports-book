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

App::uses('User', 'Model');

class UsersControllerTest extends ControllerTestCase
{
    /** @var UsersController  $_Users */
    private $_Users;

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
     * Valid user login data
     *
     * @var array
     */
    private $_ValidUser = array(
        'User'  =>  array(
            'username'  =>  'admin',
            'password'  =>  'admin'
        )
    );

    /**
     * Not valid user login data
     *
     * @var array
     */
    private $_InvalidUser = array(
        'User'  =>  array(
            'username'  =>  'not_existing_username',
            'password'  =>  'not_existing_password'
        )
    );

    /**
     * User Login Test
     *
     * @return void
     */
    public function testLogin()
    {
        $this->_Users = $this->generate('Users',
            array(
                'components' => array(
                    'Security' => array( '_validatePost', '_validateCsrf')
                )
            )
        );

        $this->testAction("/users/login",
            array(
                "method"    => "post",
                "return"    => "contents",
                "data"      => $this->_InvalidUser
            )
        );

        $this->assertEqual($this->_Users->Auth->user(), null);

        $content = $this->testAction("/users/login",
            array(
                "method"    => "post",
                "return"    => "contents",
                "data"      => $this->_ValidUser
            )
        );


        $this->assertEqual($this->_Users->Auth->user('username'), $this->_ValidUser['User']['username']);
    }

    /**
     * Tear down any static object changes and restore them.
     * Override original method to keep case data (sessions, etc)
     *
     * @return void
     */
    public function tearDown() { }
}