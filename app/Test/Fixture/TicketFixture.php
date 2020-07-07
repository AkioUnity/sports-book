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

class TicketFixture extends CakeTestFixture {

    /**
     *
     * @var $import string
     */
    public $import = 'Ticket';

    /**
     * Initialize the fixture.
     *
     * @return void
     */
    public function init()
    {
        $this->records = array(
            0   =>  array(
                'id'        =>  1,
                'date'      =>  gmdate('Y-m-d H:i:s'),
                'user_id'   =>  1,
                'odd'       =>  '5.25',
                'amount'    =>  '1.00',
                'return'    =>  '5.25',
                'type'      =>  Ticket::TICKET_TYPE_SINGLE,
                'status'    =>  -2,
                'printed'   =>  0,
                'paid'      =>  Ticket::UNPAID
            ),
            1   =>  array(
                'id'        =>  2,
                'date'      =>  gmdate('Y-m-d H:i:s'),
                'user_id'   =>  1,
                'odd'       =>  '6.25',
                'amount'    =>  '2.00',
                'return'    =>  '9.25',
                'type'      =>  Ticket::TICKET_TYPE_MULTI,
                'status'    =>  -2,
                'printed'   =>  0,
                'paid'      =>  Ticket::UNPAID
            )
        );

        parent::init();
    }
}