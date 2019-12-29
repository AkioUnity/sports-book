<div id="main" class="ticket-print">
    <h1><?php echo Configure::read('Settings.websiteName'); ?></h1>
    <h2><?php __('Ticket'); ?></h2>

    <table>
        <tr>
            <td><?php echo __('Date and time'); ?></td>
            <td class="align-right"><?php echo $this->TimeZone->convertDateTime(time()); ?></td>
        </tr>
        <tr>
            <td><?php echo __('Reservation Ticket ID'); ?></td>
            <td class="align-right"><?php printf('%1$07d', $ticket['ReservationTicket']['id']); ?></td>
        </tr>
    </table>

    <table class="bets">
        <tr>
            <th><?php echo __('ID / Date'); ?></th>
            <th><?php echo __('Event'); ?></th>
            <th><?php echo __('Pick'); ?></th>
            <th><?php echo __('Odd'); ?></th>
        </tr>
        <?php foreach ($ticket['ReservationTicketPart'] as $ticketPart): ?>
            <tr>
                <td>
                    <?php echo __('ID:'); ?> <?php echo $ticketPart['Event']['id']; ?>
                    <br />
                    <?php echo $this->TimeZone->convertDate($ticketPart['Event']['date']); ?>
                </td>
                <td>
<!--                    --><?php //if($ticketPart['Event']['feed_type'] == 'OddService'): ?>
                        <?php echo $ticketPart['Event']['name']; ?>
                        <br />
<!--                    --><?php //endif; ?>
                    <?php echo $ticketPart['Bet']['name']; ?>

                    <?php echo $ticketPart['BetPart']['line']; ?>
                </td>
                <td><?php echo $ticketPart['BetPart']['name']; ?></td>
                <td class="no-padding"><?php echo $ticketPart['BetPart']['odd']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <table class="odds">
        <tr>
            <td class="align-right"><?php echo __('Total'); ?>: <?php echo $this->Beth->convertOdd($ticket['ReservationTicket']['odd']); ?></td>
        </tr>
        <?php if(intval(Configure::read('Settings.service_fee')) > 0): ?>
            <tr>
                <td class="align-right">
                    <?php echo __('Service fee ( %s %s ): %s %s', Configure::read('Settings.service_fee'), '%', $ticket['ReservationTicket']['amount'] - $this->Beth->calculatePercentageGiven($ticket['Ticket']['amount'], Configure::read('Settings.service_fee'), Configure::read('Settings.service_fee'), intval(Configure::read('Settings.balance_decimal_places'))), Configure::read('Settings.currency')); ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="align-right"><?php echo __('Total amount'); ?>: <?php echo $ticket['ReservationTicket']['amount']; ?> <?php echo Configure::read('Settings.currency'); ?></td>
        </tr>
        <tr>
            <td class="align-right">
                <?php echo __('Return:'); ?>
                <?php echo $this->Beth->convertCurrency($ticket['ReservationTicket']['return']) . ' ' .  Configure::read('Settings.currency'); ?>
            </td>
        </tr>
    </table>
    <br />
</div>