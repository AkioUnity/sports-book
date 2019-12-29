<div id="main" class="ticket-print">
    <h1><?php echo Configure::read('Settings.websiteName'); ?></h1>
    <h2><?php __('Ticket'); ?></h2>

    <table>
        <tr>
            <td><?php echo __('Date and time'); ?></td>
            <td class="align-right"><?php echo $ticket['Ticket']['date']; ?></td>
        </tr>
        <tr>
            <td><?php echo __('Ticket id'); ?></td>
            <td class="align-right"><?php printf('%1$07d', $ticket['Ticket']['id']); ?></td>
        </tr>
    </table>

    <table class="bets">
        <tr>
            <th><?php echo __('Event ID / Date'); ?></th>
            <th><?php echo __('Event'); ?></th>
            <th><?php echo __('Pick'); ?></th>
            <th><?php echo __('Odd'); ?></th>
        </tr>
        <?php foreach ($ticket['TicketPart'] as $ticketPart): ?>
            <tr>
                <td>
                    <?php echo __('ID:'); ?> <?php echo $ticketPart['Event']['id']; ?>
                    <br />
                    <?php echo $ticketPart['Event']['date']; ?>
                </td>
                <td><?php echo $ticketPart['Bet']['name']; ?></td>
                <td><?php echo $ticketPart['BetPart']['name']; ?></td>
                <td class="no-padding"><?php echo $ticketPart['BetPart']['odd']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <table class="odds">
        <tr>
            <td></td>
            <td class="align-right"><?php echo __('Total:'); ?> <?php echo $ticket['Ticket']['odd']; ?></td>
        </tr>
        <tr>
            <td></td>
            <td class="align-right"><?php echo __('Amount:'); ?> <?php echo $ticket['Ticket']['amount']; ?> <?php echo $currency; ?></td>
        </tr>
        <tr>
            <td></td>
            <td class="align-right">
                <?php echo __('Return:'); ?> 
                <?php echo $this->Beth->convertCurrency($ticket['Ticket']['return']) . ' ' . $currency; ?>
            </td>
        </tr>
    </table>
</div>