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

class GroupFixture extends CakeTestFixture
{
    /**
     *
     * @var $import string
     */
    public $import = 'Group';

    /**
     * Initialize the fixture.
     *
     * @return void
     */
    public function init()
    {
        $this->records = array(
            0   =>  array(
                'id'        =>  Group::GUEST_GROUP,
                'name'      =>  'Guest',
                'created'   =>  '2012-12-12 00:00:00',
                'modified'  =>  '2012-12-12 00:00:00'
            ),
            1   =>  array(
                'id'        =>  Group::USER_GROUP,
                'name'      =>  'User',
                'created'   =>  '2012-12-12 00:00:00',
                'modified'  =>  '2012-12-12 00:00:00'
            ),
            2   =>  array(
                'id'        =>  Group::ADMINISTRATOR_GROUP,
                'name'      =>  'Administrator',
                'created'   =>  '2012-12-12 00:00:00',
                'modified'  =>  '2012-12-12 00:00:00'
            ),
            3   =>  array(
                'id'        =>  Group::OPERATOR_GROUP,
                'name'      =>  'Operator',
                'created'   =>  '2012-12-12 00:00:00',
                'modified'  =>  '2012-12-12 00:00:00'
            ),
            4   =>  array(
                'id'        =>  Group::CASHIER_GROUP,
                'name'      =>  'Cashier',
                'created'   =>  '2012-12-12 00:00:00',
                'modified'  =>  '2012-12-12 00:00:00'
            )
        );

        parent::init();
    }
}