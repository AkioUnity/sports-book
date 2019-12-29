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

class UserFixture extends CakeTestFixture
{
    /**
     *
     * @var $import string
     */
    public $import = 'User';

    /**
     * Initialize the fixture.
     *
     * @return void
     */
    public function init()
    {
        $this->records = array(
            0   =>  array(
                'id'                => 1,
                'username'          => 'admin',
                'password'          => '99cdff0cb5b68bb1e2dacb35ce5c6769eadf871b',
                'email'             => 'admin@chalkpro.com',
                'balance'           => '9999',
                'time_zone'         => '0.00',
                'group_id'          =>  3,
                'language_id'       =>  1,
                'currency_id'       =>  1,
                'odds_type'         =>  0,
                'allow_betslip'     =>  1,
                'day_stake_limit'   =>  0,
                'status'            =>  1,
                'referal_id'        =>  0,
                'ip'                =>  '',
                'last_visit'        =>  '2014-01-01 00:00:00',
                'registration_date' =>  '2013-01-01 00:00:00',
                'personal_question' =>  '',
                'personal_answer'   =>  '',
                'first_name'        =>  '',
                'last_name'         =>  '',
                'date_of_birth'     =>  '',
                'address1'          =>  '',
                'address2'          =>  '',
                'zip_code'          =>  '',
                'city'              =>  '',
                'country'           =>  '',
                'mobile_number'     =>  '',
                'confirmation_code' =>  '',
                'bank_name'         =>  '',
                'account_number'    =>  '',
                'bank_code'         =>  '',
                'ticket_count'      =>  0
            )
        );

        parent::init();
    }
}