<?php
/**
 * TicketPart Model
 *
 * Handles TicketPart Data Source Actions
 *
 * @package    TicketParts.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class ReservationTicketPart extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'ReservationTicketPart';

    /**
     *
     */
    public $table = 'reservation_tickets_parts';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'reservation_ticket_id' => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'event_id'      => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => false
        ),
        'bet_part_id'   => array(
            'type'      => 'bigint',
            'length'    => 20,
            'null'      => false
        ),
        'odd'           => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => false
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array('ReservationTicket', 'BetPart');

    /**
     * Updates bet part status by id
     *
     * @param $id
     * @param $status
     * @param bool $updateTicket
     */
    public function setStatus($id, $status, $updateTicket = true)
    {
        $this->create();

        $data = $this->find('first', array(
            'conditions'    =>
                array(
                'TicketPart.id' =>  $id,
            ),
            'recursive'     =>  -1
        ));

        if(!empty($data))
        {
            $this->id = $id;

            $data['TicketPart']['status'] = $status;

            $this->save($data);

            if($updateTicket !== false) {
                $this->ReservationTicket->updateStatusByBets($data['TicketPart']['ticket_id']);
            }
        }
    }

    /**
     * Updates odd for betPart
     *
     * @param int   $ticketPartId - BetPartId
     * @param float $Odd          - Odd value
     *
     * @return void
     */
    public function setOdd($ticketPartId, $Odd)
    {
        $this->create();

        $this->id = $ticketPartId;

        $data['TicketPart']['odd'] = $Odd;

        $this->save($data);
    }

    /**
     * Updates bet part status by bet part id
     *
     * @param int  $betPartId    - betPartId
     * @param int  $status       - bet status
     * @param bool $updateTicket - do update ticket?
     *
     * @return void
     */
    public function setStatusByBetPartId($betPartId, $status, $updateTicket = true)
    {
        $this->create();

        $ticketParts = $this->find('all',
            array(
                'conditions'    =>  array(
                    'TicketPart.bet_part_id' =>  $betPartId,
                ),
                'recursive'     =>  -1
            )
        );

        if (!empty($ticketParts)) {
            foreach ($ticketParts AS $ticketPart) {
                $this->create();

                $this->id = $ticketPart['TicketPart']['id'];

                $ticketPart['TicketPart']['status'] = $status;

                $this->save($ticketPart);

                if ($updateTicket !== false) {
                    $this->ReservationTicket->updateStatusByBets(
                        $ticketPart['TicketPart']['ticket_id']
                    );
                }
            }
        }
    }

    /**
     * Gets pending bet parts
     *
     * @return array
     */
    public function getPendingBetParts()
    {
        $options['conditions'] = array(
            'TicketPart.status' => 0
        );
        $options['fields'] = array(
            'TicketPart.id',
            'TicketPart.bet_part_id'
        );
        $options['group'] = 'TicketPart.bet_part_id';
        return $this->find('list', $options);
    }
}