<?php
 /**
 * Feed Event Listener
 *
 * Holds Feed Event Listener Methods
 *
 * @package    Feed
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('CakeEventListener', 'Event');

class OddsServiceListener implements CakeEventListener
{
    /**
     * Ticket Model
     *
     * @var Ticket $Ticket
     */
    protected $Ticket;

    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents() {
        return array(
            'Shell.OddService.ImportEvent.insertEvent'      =>  'insertEvent',
            'Shell.OddService.ImportEvent.insertBetPart'    =>  'insertBetPart'
        );
    }

    /**
     * Insert Event
     * Event fired after bet event insertion
     *
     *  * Updates ticket score
     *
     * @param CakeEvent $event
     */
    public function insertEvent(CakeEvent $event)
    {
        switch( strtolower( (string) $event->data['Event']->Status ) )
        {
            case OddService::EVENT_STATUS_FINISHED:

                if ($event->data['updateResults'] == true) {
                    foreach($event->data['Event']->Scores->xpath('Score') AS $Score) {
                        switch( strtolower ( (string) $Score->attributes()->period ) ) {
                            case 'ft':
                                $event->subject()->Event->setResult($event->data['EventId'], (string) $Score->attributes()->homeScore . ' - ' . (string) $Score->attributes()->awayScore);
                                break;
                        }
                    }
                }

                break;

            case OddService::EVENT_STATUS_CANCELLED:

                $Event = $event->subject()->Event->getItem($event->data['EventId'], array('Bet' => array('BetPart')));

                $BetPartIds = array();

                foreach ($Event['Bet'] AS $Bet) {
                    if (is_array($Bet['BetPart']) && !empty($Bet['BetPart'])) {
                        foreach ($Bet['BetPart'] AS $BetPart) {
                            $BetPartIds[] = $BetPart['id'];
                        }
                    }

                    $Ticket = $event->subject()->Ticket->TicketPart->find('all', array(
                        'fields'        =>  array('id', 'ticket_id'),
                        'conditions'    =>  array(
                            'TicketPart.bet_part_id'    =>  $BetPartIds
                        )
                    ));

                    if (!empty($Ticket)) {
                        foreach ($Ticket AS $TicketPart) {
                            if (isset($TicketPart['TicketPart']['ticket_id'])) {
                                $event->subject()->Ticket->cancel($TicketPart['TicketPart']['ticket_id'], array($TicketPart['TicketPart']['id']));
                            }
                        }
                    }
                }

                break;

            case OddService::EVENT_STATUS_POSTPONED:
                if ($event->data['updateResults'] == true) {
                    $event->subject()->Event->setResult('Postponed');
                }
                break;

            default:
                if ($event->data['updateResults'] != true) {
                    $event->subject()->Event->setResult('');
                }
                break;
        }
    }

    /**
     * Insert Bet Part Event
     * Event fired after bet part insertion
     *
     *  * Updates ticket part
     *
     * @param CakeEvent $event
     * @return bool
     */
    public function insertBetPart(CakeEvent $event)
    {
        if( is_object( $event->data['isWinner'] ) && $event->data['isWinner'] != null )
        {
            $this->Ticket = ClassRegistry::init('Ticket');

            $OddStatus = $event->subject()->OddService->getOddStatus( (int) $event->data['isWinner'] );

            $this->Ticket->TicketPart->setStatusByBetPartId( $event->data['BetPartId'], $OddStatus );
        }
    }
}