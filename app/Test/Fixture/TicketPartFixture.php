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

class TicketPartFixture extends CakeTestFixture
{
    /**
     *
     * @var $import string
     */
    public $import = 'TicketPart';

    /**
     * Initialize the fixture.
     *
     * @return void
     */
    public function init()
    {
        $this->records = array(
            0   =>  array(
                'id'            => 1,
                'ticket_id'     => 1,
                'bet_part_id'   => 1,
                'odd'           => 1,
                'status'        => ''
            )
        );

        parent::init();
    }
}