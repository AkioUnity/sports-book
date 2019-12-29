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

class CurrencyFixture extends CakeTestFixture
{
    /**
     *
     * @var $import string
     */
    public $import = 'Currency';

    /**
     * Initialize the fixture.
     *
     * @return void
     */
    public function init()
    {
        $this->records = array(
            0   =>  array(
                'id'    =>  1,
                'name'  =>  'USD',
                'code'  =>  '$',
                'rate'  =>  '1.00000'
            ),
            1   =>  array(
                'id'    =>  2,
                'name'  =>  'Naira',
                'code'  =>  '₦',
                'rate'  =>  '1.00000'
            ),
            2   =>  array(
                'id'    =>  3,
                'name'  =>  'Euro',
                'code'  =>  '€',
                'rate'  =>  '1.00000'
            )
        );

        parent::init();
    }
}